<?php

namespace App\Extensions\TitanOperator\System\Agent\Http\Controllers;

use App\Extensions\TitanOperator\System\Enums\TicketStatusEnum;
use App\Extensions\TitanOperator\System\Http\Resources\Admin\TitanOperatorConversationResource;
use App\Extensions\TitanOperator\System\Http\Resources\Api\TitanOperatorHistoryResource;
use App\Extensions\TitanOperator\System\Models\TitanOperator;
use App\Extensions\TitanOperator\System\Models\TitanOperatorChannel;
use App\Extensions\TitanOperator\System\Models\TitanOperatorConversation;
use App\Extensions\TitanOperator\System\Models\TitanOperatorCustomer;
use App\Extensions\TitanOperator\System\Models\TitanOperatorHistory;
use App\Extensions\TitanOperator\System\Services\TitanOperatorService;
use App\Extensions\TitanOperator\System\Agent\Services\TitanOperatorForFrameEventAbly;
use App\Extensions\TitanOperatorTelegram\System\Services\Telegram\TelegramService;
use App\Extensions\TitanOperatorWhatsapp\System\Services\Twillio\TwilioWhatsappService;
use App\Helpers\Classes\Helper;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Throwable;

class TitanOperatorAgentController extends Controller
{
    public function __construct(public TitanOperatorService $service) {}

    public function index(Request $request)
    {
        return view('titan_operator_agent::index');
    }

    public function notification(Request $request): JsonResponse
    {
        $operators = $request->user()->externalTitanOperators->pluck('id')->toArray();

        $count = TitanOperatorConversation::query()
            ->whereHas('histories', function ($query) {
                $query->whereNull('read_at');
            })
            ->whereIn('operator_id', $operators)
            ->count();

        return response()->json([
            'class'  => 'hidden',
            'count'  => $count,
            'status' => 'success',
        ]);
    }

    public function closed(Request $request): TitanOperatorConversationResource|JsonResponse
    {
        if (Helper::appIsDemo()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'This feature is disabled in Demo version.',
            ], 403);
        }

        $request->validate([
            'conversation_id'   => 'required|exists:tz_portal_operator_conversations,id',
        ]);

        $conversation = TitanOperatorConversation::query()->find($request['conversation_id']);

        $conversation->update([
            'ticket_status' => TicketStatusEnum::closed->value,
        ]);

        return TitanOperatorConversationResource::make($conversation)->additional([
            'message' => 'This feature is disabled in free version.',
            'status'  => 'success',
        ]);
    }

    public function pinned(Request $request): TitanOperatorConversationResource|JsonResponse
    {
        if (Helper::appIsDemo()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'This feature is disabled in Demo version.',
            ], 403);
        }

        $request->validate([
            'conversation_id'   => 'required|exists:tz_portal_operator_conversations,id',
        ]);

        $conversation = TitanOperatorConversation::query()->find($request['conversation_id']);

        $maxPinned = TitanOperatorConversation::query()
            ->max('pinned') ?? 0;

        $currentPinned = $conversation->getAttribute('pinned') ?? 0;

        if ($currentPinned > 0) {
            $newPinnedValue = 0;
        } else {
            $newPinnedValue = $maxPinned + 1;
        }

        $conversation->update([
            'pinned' => $newPinnedValue,
        ]);

        $message = $newPinnedValue > 0
            ? trans('Conversation pinned.')
            : trans('Conversation unpinned.');

        return TitanOperatorConversationResource::make($conversation)->additional([
            'message' => $message,
            'status'  => 'success',
        ]);
    }

    public function name(Request $request): TitanOperatorConversationResource|JsonResponse
    {
        if (Helper::appIsDemo()) {
            return response()->json([
                'status'  => 'error',
                'type'    => 'error',
                'message' => 'This feature is disabled in Demo version.',
            ], 403);
        }

        $request->validate([
            'conversation_id'   => 'required|exists:tz_portal_operator_conversations,id',
            'conversation_name' => 'required|string',
        ]);

        $conversation = TitanOperatorConversation::query()->find($request['conversation_id']);

        if ($conversation->customer) {
            $conversation->customer?->update([
                'name' => $request['conversation_name'],
            ]);
        }

        $conversation->update(['conversation_name' => $request['conversation_name']]);

        return TitanOperatorConversationResource::make($conversation);
    }

    public function update(Request $request): TitanOperatorConversationResource|JsonResponse
    {
        if (Helper::appIsDemo()) {
            return response()->json([
                'status'  => 'error',
                'type'    => 'error',
                'message' => 'This feature is disabled in Demo version.',
            ], 403);
        }

        $request->validate([
            'conversation_id'   => 'required|integer|exists:tz_portal_operator_conversations,id',
            'conversation_name' => 'sometimes|string',
            'color'             => 'sometimes|string',
        ]);

        $conversation = TitanOperatorConversation::query()->find($request['conversation_id']);

        $conversation->update($request->only(['conversation_name', 'color']));

        $customer = $this->createCustomer($conversation->titan_operator, $conversation->getAttribute('session_id'));

        $customer->update([
            'name' => $request['conversation_name'] ?? $customer->name,
        ]);

        return TitanOperatorConversationResource::make($conversation);
    }

    private function createCustomer(TitanOperator $titan_operator, string $session)
    {
        return TitanOperatorCustomer::query()->firstOrCreate([
            'user_id'         => $titan_operator->getAttribute('user_id'),
            'operator_id'      => $titan_operator->getAttribute('id'),
            'session_id'      => $session,
            'operator_channel' => 'frame',
        ]);
    }

    public function store(Request $request): TitanOperatorHistoryResource|JsonResponse
    {
        if (Helper::appIsDemo()) {
            return response()->json([
                'status'  => 'error',
                'type'    => 'error',
                'message' => 'This feature is disabled in Demo version.',
            ], 403);
        }

        $request->validate([
            'conversation_id' => 'required|integer|exists:tz_portal_operator_conversations,id',
            'message'         => 'sometimes|nullable|string',
            'media'           => 'sometimes|nullable|mimes:' . setting('media_allowed_types', 'jpg,png,gif,webp,svg,mp4,avi,mov,wmv,flv,webm,mp3,wav,m4a,pdf,doc,docx,xls,xlsx') . '|max:20480',
        ]);

        $mediaUrl = null;
        $mediaName = null;

        if ($request->hasFile('media')) {
            $mediaName = $request->file('media')->getClientOriginalName();
            $mediaUrl = '/uploads/' . $request->file('media')->store('titan_operator-media', 'public');
        }

        $operatorConversation = TitanOperatorConversation::query()
            ->with('titan_operator')
            ->find($request['conversation_id']);

        $history = TitanOperatorHistory::query()->create([
            'user_id'         => Auth::id(),
            'operator_id'      => $operatorConversation->getAttribute('operator_id'),
            'conversation_id' => $operatorConversation->getAttribute('id'),
            'model'           => $operatorConversation->titan_operator->getAttribute('ai_model'),
            'media_url'       => $mediaUrl,
            'media_name'      => $mediaName,
            'role'            => 'assistant',
            'message'         => $request['message'],
            'created_at'      => now(),
        ]);

        try {
            if ($operatorConversation->getAttribute('operator_channel_id')) {
                /**
                 * @var TitanOperatorChannel $operatorChannel
                 */
                $operatorChannel = $operatorConversation->getAttribute('operatorChannel');

                if ($operatorChannel) {
                    if ($operatorChannel?->channel === 'whatsapp' && $operatorConversation->getAttribute('customer_channel_id')) {
                        app(TwilioWhatsappService::class)
                            ->setTitanOperatorChannel($operatorChannel)
                            ->sendText(
                                $request['message'],
                                $operatorConversation->getAttribute('customer_channel_id')
                            );
                    }
                    if ($operatorChannel?->channel === 'telegram') {
                        app(TelegramService::class)
                            ->setChannel($operatorChannel)
                            ->sendText(
                                $request['message'],
                                $operatorConversation->getAttribute('customer_channel_id')
                            );
                    }
                }

            } else {
                TitanOperatorForFrameEventAbly::dispatch($history, $operatorConversation->sessionId());
            }
        } catch (Exception $e) {
        }

        return TitanOperatorHistoryResource::make($history)->additional([
            'message' => 'Message was sent.',
            'status'  => 'success',
        ]);
    }

    public function conversations(Request $request): AnonymousResourceCollection
    {
        $operators = $request->user()->externalTitanOperators->pluck('id')->toArray();

        $conversations = $this->service->agentConversations($operators, 'updated_at');

        return TitanOperatorConversationResource::collection($conversations);
    }

    public function conversationsWithPaginate(Request $request): AnonymousResourceCollection
    {
        $operators = $request->user()->externalTitanOperators->pluck('id')->toArray();

        $conversations = $this->service->agentConversationsWithPaginate($operators);

        $count = $this->service->agentConversationsWithQuery($operators)
            ->selectRaw('ticket_status, count(*) as count')
            ->groupBy('ticket_status')
            ->pluck('count', 'ticket_status');

        $new = $count?->get('new', 0);

        $closed = $count?->get('closed', 0);

        return TitanOperatorConversationResource::collection($conversations)->additional([
            'status_count' => [
                'all'    => $new + $closed,
                'new'    => $new,
                'closed' => $closed,
            ],
        ]);
    }

    public function conversationsHistorySession(Request $request): AnonymousResourceCollection
    {
        $request->validate(['sessionId' => 'required|string']);

        $conversations = $this->service->historyConversationsWithPaginate(
            sessionId: $request->sessionId
        );

        return TitanOperatorConversationResource::collection($conversations);
    }

    public function history(Request $request): AnonymousResourceCollection
    {
        $request->validate(['conversation_id' => 'required|integer|exists:tz_portal_operator_conversations,id']);

        TitanOperatorHistory::query()->where('conversation_id', request('conversation_id'))->update(['read_at' => now()]);

        $conversation = TitanOperatorConversation::query()->find(request('conversation_id'));

        return TitanOperatorHistoryResource::collection($conversation->getAttribute('histories'));
    }

    public function searchConversation(Request $request)
    {
        $operators = $request->user()->externalTitanOperators->pluck('id')->toArray();

        $conversations = $this->service->agentConversationsBySearch($operators, $request->search ?? '');

        return TitanOperatorConversationResource::collection($conversations);
    }

    public function destroy(Request $request): JsonResponse
    {
        if (Helper::appIsDemo()) {
            return response()->json([
                'status'  => 'error',
                'type'    => 'error',
                'message' => 'This feature is disabled in Demo version.',
            ], 403);
        }

        try {
            $request->validate(['conversation_id' => 'required|integer|exists:tz_portal_operator_conversations,id']);

            TitanOperatorConversation::query()->find(request('conversation_id'))?->delete();

            return response()->json([
                'status'  => 'success',
                'message' => 'Successfully removed conversation',
            ]);
        } catch (Throwable $th) {
            return response()->json([
                'status'       => 'error',
                'message'      => 'Something went wrong',
                'errorMessage' => $th->getMessage(),
            ]);
        }

    }
}
