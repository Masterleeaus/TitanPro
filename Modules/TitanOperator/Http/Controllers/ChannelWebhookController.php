<?php

namespace Modules\TitanOperator\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\TitanOperator\Models\Conversation;

class ChannelWebhookController extends Controller
{
    public function __invoke(Request $request, int $operatorId, int $channelId, string $channel): JsonResponse
    {
        $sessionId = (string) ($request->input('session_id') ?: $request->input('from') ?: $request->ip() ?: uniqid('session_', true));

        Conversation::query()->firstOrCreate([
            'operator_id' => $operatorId,
            'session_id' => $sessionId,
        ], [
            'chatbot_channel_id' => $channelId,
            'last_activity_at' => now(),
        ]);

        return response()->json([
            'status' => 'accepted',
            'operator_id' => $operatorId,
            'channel_id' => $channelId,
            'channel' => $channel,
            'session_id' => $sessionId,
        ]);
    }
}
