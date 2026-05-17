<?php

declare(strict_types=1);

namespace App\Extensions\MarketingBot\System\Http\Controllers\Conversation;

use App\Extensions\MarketingBot\System\Models\MarketingConversation;
use App\Extensions\MarketingBot\System\Voice\VoiceCommandRouter;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VoiceCommandController extends Controller
{
    public function __invoke(Request $request, VoiceCommandRouter $voiceCommandRouter): JsonResponse
    {
        $request->validate([
            'conversation_id' => 'required|integer',
            'transcript' => 'required|string',
        ]);

        $conversation = MarketingConversation::query()->findOrFail($request->integer('conversation_id'));
        $this->authorize('update', $conversation);

        $result = $voiceCommandRouter->handle($conversation, $request->string('transcript')->toString());

        return response()->json([
            'status' => 'success',
            'reply' => $result['reply'] ?? '',
            'voice_transcript' => $result['voice_transcript'] ?? '',
            'command' => $result['command'] ?? [],
            'signal' => $result['signal'] ?? [],
            'titantalk' => [
                'intent' => $result['classification']['intent']->value ?? null,
                'goal' => $result['classification']['goal'] ?? null,
                'state' => $result['classification']['next_state'] ?? null,
                'tool_plans' => $result['tool_plans'] ?? [],
                'safety' => $result['safety'] ?? [],
                'sequence' => $result['sequence'] ?? [],
                'context_summary' => $result['context']['summary'] ?? null,
                'context' => $result['context'] ?? [],
            ],
        ]);
    }
}
