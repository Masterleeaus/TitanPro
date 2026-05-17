<?php

declare(strict_types=1);

namespace App\Extensions\MarketingBot\System\Http\Controllers\Conversation;

use App\Extensions\MarketingBot\System\Models\MarketingConversation;
use App\Extensions\MarketingBot\System\Models\TitanTalk\Handoff;
use App\Extensions\MarketingBot\System\Services\Conversation\AiChatbotService;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AssistantController extends Controller
{
    public function __invoke(Request $request, AiChatbotService $chatbotService): JsonResponse
    {
        $request->validate([
            'conversation_id' => 'required|integer',
            'message' => 'required|string',
        ]);

        $conversation = MarketingConversation::query()->findOrFail($request->integer('conversation_id'));
        $this->authorize('update', $conversation);

        $result = $chatbotService->replyWithContext($conversation, $request->string('message')->toString());
        $freshConversation = $conversation->fresh();
        $handoff = Handoff::query()->where('conversation_id', $conversation->getKey())->whereIn('state', ['open', 'assigned'])->latest('id')->first();

        return response()->json([
            'status' => 'success',
            'reply' => $result['reply'],
            'conversation' => $freshConversation,
            'titantalk' => [
                'intent' => $freshConversation?->intent,
                'state' => $freshConversation?->state,
                'goal' => $freshConversation?->goal,
                'role_pack' => $freshConversation?->role_pack,
                'handoff_requested' => $freshConversation?->handoff_requested_at !== null,
                'active_handoff_id' => $handoff?->id,
                'active_handoff_state' => $handoff?->state,
                'intent_confidence' => $result['classification']['confidence'] ?? null,
                'entities' => $result['classification']['entities'] ?? [],
                'tool_plans' => $result['tool_plans'] ?? [],
                'command' => $result['command'] ?? [],
                'safety' => $result['safety'] ?? [],
                'sequence' => $result['sequence'] ?? [],
                'signal' => $result['signal'] ?? [],
                'context_summary' => $result['context']['summary'] ?? null,
                'context' => $result['context'] ?? [],
            ],
        ]);
    }
}
