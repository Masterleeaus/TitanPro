<?php

namespace App\Extensions\TitanOperator\System\Http\Controllers;

use App\Extensions\TitanOperator\System\Http\Requests\TitanOperatorCustomizeRequest;
use App\Extensions\TitanOperator\System\Http\Requests\TitanOperatorStoreRequest;
use App\Extensions\TitanOperator\System\Http\Resources\Admin\TitanOperatorConversationResource;
use App\Extensions\TitanOperator\System\Http\Resources\Admin\TitanOperatorResource;
use App\Extensions\TitanOperator\System\Models\TitanOperator;
use App\Extensions\TitanOperator\System\Services\TitanOperatorService;
use App\Helpers\Classes\Helper;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TitanOperatorController extends Controller
{
    public function __construct(public TitanOperatorService $service) {}

    public function index(Request $request): View
    {
        if (method_exists(Helper::class, 'appIsDemoForTitanOperator')) {
            if (Helper::appIsDemoForTitanOperator()) {
                $this->clearDemoData();
            }
        }

        $externalTitanOperators = $request->user()->externalTitanOperators->pluck('id')->toArray();
        $unreadAgentMessagesCount = $this->service->unreadAgentMessagesCount($externalTitanOperators);
        $unreadAiBotMessagesCount = $this->service->unreadAiBotMessagesCount($externalTitanOperators);
        $allMessagesCount = $this->service->allMessagesCount($externalTitanOperators);

        return view('titan_operator::index', [
            'operators' => $this->service->query()
                ->with('channels:id,operator_id,channel')
                ->where('user_id', Auth::id())
                ->orderBy('created_at', 'desc')
                ->paginate(perPage: 100),
            'avatars'                  => $this->service->avatars(),
            'unreadAgentMessagesCount' => $unreadAgentMessagesCount,
            'unreadAiBotMessagesCount' => $unreadAiBotMessagesCount,
            'allMessagesCount'         => $allMessagesCount,
        ]);
    }

    public function enbed(TitanOperator $titan_operator): View
    {
        // Embed helper for admins/operators. Kept as a separate screen for legacy route compatibility.
        $this->authorize('update', $titan_operator);

        $iframeUrl = route('titan_operator.frame', $titan_operator->getAttribute('uuid'));

        // Simple embed snippet (iframe). Consumers can wrap in their own container.
        $embedCode = '<iframe src="' . e($iframeUrl) . '" style="width:100%;height:700px;border:0;border-radius:12px;overflow:hidden;" loading="lazy"></iframe>';

        return view('titan_operator::dashboard.embed', [
            'titan_operator'    => $titan_operator,
            'iframeUrl'  => $iframeUrl,
            'embedCode'  => $embedCode,
        ]);
    }


    public function store(TitanOperatorStoreRequest $request): JsonResponse|TitanOperatorResource
    {
        $titan_operator = $this->service->query()->create($request->validated());

        return TitanOperatorResource::make($titan_operator);
    }

    public function update(TitanOperatorCustomizeRequest $request): JsonResponse|TitanOperatorResource
    {
        if (Helper::appIsDemo()) {
            return response()->json([
                'type'    => 'error',
                'message' => 'This feature is disabled in Demo version.',
            ], 403);
        }

        $data = $request->validated();

        $titan_operator = $this->service->query()->findOrFail($data['id']);

        $this->authorize('update', $titan_operator);

        if ($request->file('header_bg_image_blob')) {
            $path = $request->file('header_bg_image_blob')->store('titan_operator', 'public');

            $data['header_bg_image'] = '/uploads/' . $path;
        }

        if ($titan_operator->getAttribute('is_demo')) {
            return response()->json([
                'type'    => 'error',
                'message' => 'This feature is disabled in Demo version.',
            ], 403);
        }

        $titan_operator = $this->service->update($data['id'], $data);

        return TitanOperatorResource::make($titan_operator);
    }

    public function conversations(Request $request): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $operators = $request->user()->externalTitanOperators->pluck('id')->toArray();

        $conversations = $this->service->conversations($operators);

        return TitanOperatorConversationResource::collection($conversations);
    }

    public function conversationsWithPaginate(Request $request): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $operators = $request->user()->externalTitanOperators->pluck('id')->toArray();

        $conversations = $this->service->conversationsWithPaginate($operators);

        return TitanOperatorConversationResource::collection($conversations);
    }

    public function searchConversation(Request $request)
    {
        $operators = $request->user()->externalTitanOperators->pluck('id')->toArray();

        $conversations = $this->service->agentConversationsBySearch($operators, $request->search ?? '');

        return TitanOperatorConversationResource::collection($conversations);
    }

    public function delete(Request $request): JsonResponse
    {

        if (Helper::appIsDemo()) {
            return response()->json([
                'type'    => 'error',
                'message' => 'This feature is disabled in Demo version.',
            ], 403);
        }

        $request->validate(['id' => 'required']);

        $titan_operator = $this->service->query()->findOrFail($request->get('id'));

        $this->authorize('delete', $titan_operator);

        if ($titan_operator->getAttribute('is_demo')) {
            return response()->json([
                'type'    => 'error',
                'message' => 'This feature is disabled in Demo version.',
            ], 403);
        }

        if ($titan_operator->getAttribute('user_id') === Auth::id()) {
            $titan_operator->delete();
        } else {
            abort(403);
        }

        return response()->json([
            'message' => 'TitanOperator deleted successfully',
            'type'    => 'success',
            'status'  => 200,
        ]);
    }

    public function clearDemoData(): void
    {
        TitanOperator::query()->where('is_demo', '=', 0)
            ->where('created_at', '<', now()->subMinutes(30))
            ->delete();
    }
}