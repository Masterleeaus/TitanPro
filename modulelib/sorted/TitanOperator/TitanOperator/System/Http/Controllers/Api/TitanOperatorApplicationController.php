<?php

namespace App\Extensions\TitanOperator\System\Http\Controllers\Api;

use App\Extensions\TitanOperator\System\Enums\InteractionType;
use App\Extensions\TitanOperator\System\Http\Requests\TitanOperatorHistoryStoreRequest;
use App\Extensions\TitanOperator\System\Http\Resources\Api\TitanOperatorConversationResource;
use App\Extensions\TitanOperator\System\Http\Resources\Api\TitanOperatorHistoryResource;
use App\Extensions\TitanOperator\System\Http\Resources\Api\TitanOperatorResource;
use App\Extensions\TitanOperator\System\Models\TitanOperator;
use App\Extensions\TitanOperator\System\Models\TitanOperatorConversation;
use App\Extensions\TitanOperator\System\Models\TitanOperatorCustomer;
use App\Extensions\TitanOperator\System\Models\TitanOperatorHistory;
use App\Extensions\TitanOperator\System\Models\TitanOperatorKnowledgeBaseArticle;
use App\Extensions\TitanOperator\System\Services\GeneratorService;
use App\Extensions\TitanOperator\System\Agent\Services\TitanOperatorForPanelEventAbly;
use App\Helpers\Classes\Helper;
use App\Helpers\Classes\MarketplaceHelper;
use App\Helpers\Classes\RateLimiter\RateLimiter;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TitanOperatorApplicationController extends Controller
{
    public Setting $setting;

    public function __construct(
        public GeneratorService $service
    ) {
        $this->setting = Setting::getCache();
    }

    private function resolveCustomer(TitanOperator $titan_operator, string $sessionId): TitanOperatorCustomer
    {
        // For new visitors the customer row may not exist yet; create on demand.
        return TitanOperatorCustomer::query()->firstOrCreate(
            [
                'session_id' => $sessionId,
                'operator_id' => $titan_operator->getAttribute('id'),
            ],
            [
                // Default fields kept minimal; can be enriched later by collectEmail/connectSupport/etc.
                'name'        => 'Visitor',
                'ip_address'  => request()->ip(),
                'payload'     => [],
            ]
        );
    }


    public function index(TitanOperator $titan_operator): TitanOperatorResource
    {
        return TitanOperatorResource::make($titan_operator);
    }

    public function enableSound(TitanOperator $titan_operator, string $sessionId): JsonResponse
    {
        $customer = $this->resolveCustomer($titan_operator, $sessionId);
$customer->update([
            'enabled_sound' => ! $customer->getAttribute('enabled_sound'),
        ]);

        return response()->json([
            'enabled_sound' => $customer->getAttribute('enabled_sound'),
        ]);
    }

    public function articles(Request $request, TitanOperator $titan_operator)
    {
        return TitanOperatorKnowledgeBaseArticle::query()
            ->whereRaw('JSON_CONTAINS(operators, ?)', ['"' . $titan_operator->getKey() . '"'])
            ->select(columns: [
                'id',
                'title',
                'description as excerpt',
                'is_featured',
                DB::raw('"#" as link'),
            ])
            ->when($request->get('search'), function ($query, $search) {
                $query->where('title', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
            })->get();
    }

    public function showArticles(Request $request, TitanOperator $titan_operator, $id)
    {
        return TitanOperatorKnowledgeBaseArticle::query()
            ->whereRaw('JSON_CONTAINS(operators, ?)', ['"' . $titan_operator->getKey() . '"'])
            ->select(columns: [
                'id',
                'title',
                'description as excerpt',
                'content',
                'is_featured',
                DB::raw('"#" as link'),
            ])
            ->where('id', $id)
            ->get();
    }

    public function storeFile(
        Request $request,
        TitanOperator $titan_operator,
        string $sessionId,
        $conversationId = null
    ): TitanOperatorHistoryResource {
        $request->validate([
            'message'         => 'sometimes|nullable|string',
            'media'           => 'required|mimes:' . setting('media_allowed_types', 'jpg,png,gif,webp,svg,mp4,avi,mov,wmv,flv,webm,mp3,wav,m4a,pdf,doc,docx,xls,xlsx') . '|max:20480',
        ]);

        $operatorConversation = TitanOperatorConversation::query()
            ->findOrFail($conversationId);

        $mediaUrl = null;
        $mediaName = null;

        if ($request->hasFile('media')) {
            $mediaName = $request->file('media')->getClientOriginalName();
            $mediaUrl = '/uploads/' . $request->file('media')->store('titan_operator-media', 'public');
        }

        $message = $this->insertMessage(
            conversation: $operatorConversation,
            message: $request['message'] ?: '',
            role: 'user',
            model: $titan_operator->getAttribute('ai_model'),
            forcePanelEvent: (bool) $operatorConversation->getAttribute('connect_agent_at'),
            mediaUrl: $mediaUrl,
            mediaName: $mediaName,
        );

        return TitanOperatorHistoryResource::make($message)->additional([
            'collect_email' => false,
        ]);
    }

    public function sendEmail(TitanOperator $titan_operator, string $sessionId, Request $request): TitanOperatorConversationResource
    {
        $request->validate([
            'email'   => 'required|email',
            'message' => 'required|string',
        ]);

        $customer = $this->resolveCustomer($titan_operator, $sessionId);
$customer->update([
            'email' => $request->get('email'),
        ]);

        $operatorConversation = TitanOperatorConversation::query()
            ->create([
                'operator_channel' 			      => 'frame',
                'is_showed_on_history'     => false,
                'country_code'             => Helper::getRequestCountryCode(),
                'ip_address'               => Helper::getRequestIp(),
                'conversation_name'        => 'Anonymous User',
                'operator_id'               => $titan_operator->getAttribute('id'),
                'session_id'               => $sessionId,
                'operator_customer_id'      => $customer?->getKey(),
                'connect_agent_at'         => now(),
                'last_activity_at'         => now(),
                'send_email_at'            => now(),
            ]);

        $history = $this->insertMessage(
            conversation: $operatorConversation,
            message: 'Customer email: ' . $request->get('email') . "\n\n" . $request->get('message'),
            role: 'user',
            model: $titan_operator->getAttribute('ai_model'),
            forcePanelEvent: (bool) $operatorConversation->getAttribute('connect_agent_at')
        );

        $this->insertMessage(
            conversation: $operatorConversation,
            message: trans('Your message has been received, and you will get a response shortly.'),
            role: 'assistant',
            model: $titan_operator->getAttribute('ai_model'),
            forcePanelEvent: (bool) $operatorConversation->getAttribute('connect_agent_at')
        );

        try {
            TitanOperatorForPanelEventAbly::dispatch($titan_operator, $operatorConversation, $history);
        } catch (Exception $e) {
        }

        return TitanOperatorConversationResource::make($operatorConversation);

    }

    public function collectEmail(TitanOperator $titan_operator, string $sessionId, Request $request): JsonResponse
    {
        $request->validate([
            'email'   => 'required|email',
        ]);

        $customer = $this->resolveCustomer($titan_operator, $sessionId);
$customer->update([
            'email' => $request->get('email'),
        ]);

        return response()->json([
            'message' => 'Email collected successfully.',
            'email'   => $customer->email,
        ]);

    }

    public function indexSession(TitanOperator $titan_operator, string $sessionId): TitanOperatorResource
    {
        $conversations = TitanOperatorConversation::query()
            ->where('operator_id', $titan_operator->getAttribute('id'))
            ->where('session_id', $sessionId)
            ->with('lastMessage')
            ->get();

        return TitanOperatorResource::make($titan_operator)->additional([
            'conversations' => TitanOperatorConversationResource::collection($conversations),
        ]);
    }

    public function connectSupport(Request $request, TitanOperator $titan_operator, string $sessionId)
    {
        if (MarketplaceHelper::isRegistered('titan_operator_agent')) {
            $request->validate(['conversation_id' => 'required|integer|exists:ext_titan_operator_conversations,id']);

            /** @var TitanOperatorConversation $conversation */
            $conversation = TitanOperatorConversation::find($request->get('conversation_id'));

            if ($titan_operator->getAttribute('interaction_type') === InteractionType::SMART_SWITCH) {
                $conversation->update(['connect_agent_at' => now()]);

                $operatorHistory = null;

                if ($titan_operator->getAttribute('connect_message')) {
                    $operatorHistory = $this->insertMessage(
                        conversation: $conversation,
                        message: trans($titan_operator->getAttribute('connect_message')),
                        role: 'assistant',
                        model: $titan_operator->getAttribute('ai_model'),
                        forcePanelEvent: true
                    );
                }

                try {
                    TitanOperatorForPanelEventAbly::dispatch($titan_operator, $conversation, $operatorHistory);
                } catch (Exception $e) {
                    Log::error($e->getMessage());
                }

                return TitanOperatorConversationResource::make($conversation)->additional([
                    'history' => $operatorHistory ? TitanOperatorHistoryResource::make($operatorHistory) : null,
                ]);
            }

            abort(404);
        }
    }

    public function conversionStore(TitanOperator $titan_operator, string $sessionId): TitanOperatorConversationResource
    {
        $customer = TitanOperatorCustomer::query()->where('session_id', $sessionId)
            ->where('operator_id', $titan_operator->getAttribute('id'))
            ->first();

        $operatorConversation = TitanOperatorConversation::query()
            ->create([
                'conversation_name'    => $customer->name ?: 'Anonymous User',
                'operator_channel'      => 'frame',
                'is_showed_on_history' => false,
                'ip_address'           => Helper::getRequestIp(),
                'country_code'         => Helper::getRequestCountryCode(),
                'operator_id'           => $titan_operator->getAttribute('id'),
                'session_id'           => $sessionId,
                'operator_customer_id'  => $customer?->getKey(),
                'connect_agent_at'     => $titan_operator->getAttribute('interaction_type') === InteractionType::HUMAN_SUPPORT ? now() : null,
                'last_activity_at'     => now(),
            ]);

        $this->insertMessage(
            conversation: $operatorConversation,
            message: $titan_operator->getAttribute('welcome_message'),
            role: 'assistant',
            model: $titan_operator->getAttribute('ai_model'),
            forcePanelEvent: (bool) $operatorConversation->getAttribute('connect_agent_at')
        );

        return TitanOperatorConversationResource::make($operatorConversation);
    }

    public function conversion(TitanOperator $titan_operator, string $sessionId, TitanOperatorConversation $operatorConversation): TitanOperatorConversationResource
    {
        if ($operatorConversation->getAttribute('operator_id') !== $titan_operator->getAttribute('id')) {
            abort(404);
        }

        if ($operatorConversation->getAttribute('session_id') !== $sessionId) {
            abort(404);
        }

        return TitanOperatorConversationResource::make($operatorConversation);
    }

    public function export(TitanOperator $titan_operator, string $sessionId, TitanOperatorConversation $operatorConversation)
    {
        $messages = TitanOperatorHistory::query()
            ->where('conversation_id', $operatorConversation->getAttribute('id'))
            ->orderBy('id')
            ->get();

        $content = '';

        foreach ($messages as $message) {
            $role = strtoupper($message->role); // örn: user / bot
            $content .= "[{$role}] " . $message->message . PHP_EOL . PHP_EOL;
        }

        $fileName = "conversation-{$operatorConversation->id}.txt";

        return response()->make($content, 200, [
            'Content-Type'        => 'text/plain',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
        ]);
    }

    public function messages(TitanOperator $titan_operator, string $sessionId, TitanOperatorConversation $operatorConversation): AnonymousResourceCollection
    {
        if ($operatorConversation->getAttribute('operator_id') !== $titan_operator->getAttribute('id')) {
            abort(404);
        }

        if ($operatorConversation->getAttribute('session_id') !== $sessionId) {
            abort(404);
        }

        $messages = TitanOperatorHistory::query()
            ->where('conversation_id', $operatorConversation->getAttribute('id'))
            ->orderByDesc('id')
            ->paginate(perPage: request('per_page', 10));

        return TitanOperatorHistoryResource::collection($messages);
    }

    public function storeMessage(TitanOperatorHistoryStoreRequest $request, TitanOperator $titan_operator, string $sessionId, TitanOperatorConversation $operatorConversation): TitanOperatorHistoryResource
    {
        if ($operatorConversation->getAttribute('operator_id') !== $titan_operator->getAttribute('id')) {
            abort(404);
        }

        if ($operatorConversation->getAttribute('session_id') !== $sessionId) {
            abort(404);
        }

        $mediaUrl = null;
        $mediaName = null;

        if ($request->hasFile('media')) {
            $mediaName = $request->file('media')->getClientOriginalName();
            $mediaUrl = '/uploads/' . $request->file('media')->store('titan_operator-media', 'public');
        }

        $userMessage = $this->insertMessage(
            conversation: $operatorConversation,
            message: $request->validated('prompt'),
            role: 'user',
            model: $titan_operator->getAttribute('ai_model'),
            forcePanelEvent: false,
            mediaUrl: $mediaUrl,
            mediaName: $mediaName,
        );

        if (! $operatorConversation->getAttribute('is_showed_on_history')) {
            $operatorConversation->update(['is_showed_on_history' => true]);
        }

        if ($operatorConversation->getAttribute('connect_agent_at')) {
            return TitanOperatorHistoryResource::make($userMessage)->additional([
                'connection'    => 'panel',
                'collect_email' => false,
                'needs_human'   => false,
            ]);
        }

        $clientIp = Helper::getRequestIp();
        $rateLimiter = new RateLimiter('titan_operator-extension', 100);

        if (Helper::appIsDemo() && ! $rateLimiter->attempt($clientIp)) {
            $response = 'This feature is disabled in the demo version. You have reached the maximum request limit for today.';
        } else {
            $response = $this->service
                ->setTitanOperator($titan_operator)
                ->setConversation($operatorConversation)
                ->setPrompt(
                    $request->validated('prompt')
                )
                ->generate();

            if (empty($response)) {
                $response = trans('Sorry, I can\'t answer right now.');
            }
        }

        $needsHuman = false;
        $needsHumanDirect = false;

        $originalResponse = $response;

        $messageToUser = $response;

        if ($titan_operator->getAttribute('interaction_type') === InteractionType::SMART_SWITCH) {
            $needsHumanDirect = (bool) preg_match('/\s*\[human-agent-direct\]\s*$/u', $response);

            $response = $needsHumanDirect
                ? preg_replace('/\s*\[human-agent\]\s*$/u', '', $response)
                : $response;

            $response = rtrim($response);

            $needsHuman = (bool) preg_match('/\s*\[human-agent\]\s*$/u', $response);
            $messageToUser = $needsHuman
                ? preg_replace('/\s*\[human-agent\]\s*$/u', '', $response)
                : $response;
            $messageToUser = rtrim($messageToUser);

            if ($needsHumanDirect) {
                $messageToUser = trans('Connecting you to a human agent…');
            }

            if ($needsHuman) {
                $needsHumanDirect = false;
                $messageToUser = trans('Sorry, I’m not able to help with this. Let me connect you to a human agent.');
            }
        }

        $message = $this->insertMessage(
            conversation: $operatorConversation,
            message: $messageToUser,
            role: 'assistant',
            model: $titan_operator->getAttribute('ai_model'),
            forcePanelEvent: false
        );

        $customer = ! $operatorConversation?->getAttribute('customer')?->getAttribute('email');

        $collectEmail = TitanOperatorHistory::query()
            ->where('conversation_id', $operatorConversation->getAttribute('id'))
            ->where('role', '!=', 'user')
            ->count() === 2 && $customer;

        return TitanOperatorHistoryResource::make($message)->additional([
            'connection'                          => 'ai',
            'collect_email'                       => $collectEmail && $titan_operator->getAttribute('is_email_collect'),
            'needs_human'                         => $needsHuman,
            'needs_human_direct'                  => $needsHumanDirect,
            'original_response'                   => $originalResponse,
        ]);
    }

    protected function insertMessage(
        TitanOperatorConversation $conversation,
        ?string $message,
        string $role,
        string $model,
        bool $forcePanelEvent = false,
        ?string $mediaUrl = null,
        ?string $mediaName = null,
        ?string $type = null,
        ?string $messageId = null,
        ?int $userId = null,
        ?string $messageType = null,
        ?string $contentType = null
    ) {
        $titan_operator = $conversation->getAttribute('titan_operator');

        $operatorHistory = TitanOperatorHistory::query()->create([
            'operator_id'      => $conversation->getAttribute('operator_id'),
            'conversation_id' => $conversation->getAttribute('id'),
            'user_id'         => $userId,
            'message_id'      => $messageId,
            'type'            => $type ?? ($conversation->getAttribute('operator_channel') ?: 'frame'),
            'message_type'    => $messageType ?? 'text',
            'content_type'    => $contentType ?? 'text',
            'role'            => $role,
            'model'           => $this->setting->openai_default_model ?: $model,
            'message'         => $message,
            'created_at'      => now(),
            'read_at'         => $conversation->getAttribute('connect_agent_at') ? null : now(),
            'media_url'       => $mediaUrl,
            'media_name'      => $mediaName,
        ]);

        $sendEvent = $conversation->getAttribute('connect_agent_at') && $titan_operator->getAttribute('interaction_type') !== InteractionType::AUTOMATIC_RESPONSE && $role === 'user';

        if ($sendEvent || $forcePanelEvent) {
            $conversation->touch();
            if (MarketplaceHelper::isRegistered('titan_operator_agent')) {
                TitanOperatorForPanelEventAbly::dispatch(
                    $titan_operator,
                    $conversation->load('lastMessage'),
                    $operatorHistory
                );
            }
        }

        return $operatorHistory;
    }
}