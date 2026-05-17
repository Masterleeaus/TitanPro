<?php

namespace Modules\TitanOperator\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\TitanOperator\Models\Channel;
use Modules\TitanOperator\Models\Conversation;

class ChannelWebhookController extends Controller
{
    public function __invoke(Request $request, int $operatorId, int $channelId, string $channel): JsonResponse
    {
        $operatorChannel = Channel::query()
            ->whereKey($channelId)
            ->where('operator_id', $operatorId)
            ->first();

        if ($operatorChannel === null) {
            abort(404);
        }

        $sessionId = (string) ($request->input('session_id') ?: $request->input('from') ?: $request->ip() ?: uniqid('session_', true));

        Conversation::query()->updateOrCreate(
            [
                'operator_id' => $operatorId,
                'session_id' => $sessionId,
            ],
            [
                'operator_channel_id' => $operatorChannel->getKey(),
                'last_activity_at' => now(),
            ]
        );

        return response()->json([
            'status' => 'accepted',
            'operator_id' => $operatorId,
            'channel_id' => $channelId,
            'channel' => $channel,
            'session_id' => $sessionId,
        ]);
    }
}
