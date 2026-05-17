<?php

namespace App\Extensions\TitanOperator\System\Agent\Services;

use App\Extensions\TitanOperator\System\Http\Resources\Api\TitanOperatorHistoryResource;
use App\Extensions\TitanOperator\System\Models\TitanOperatorHistory;
use App\Extensions\TitanOperator\System\Agent\Services\Contracts\AblyService;

class TitanOperatorForFrameEventAbly extends AblyService
{
    public static string $chanel = 'conversation-session-';

    public static function dispatch(TitanOperatorHistory $operatorHistory, string $sessionId): void
    {
        $ably = self::ablyRest();

        $channel = $ably->channels->get(
            self::$chanel . $sessionId
        );

        $channel->publish('new-message', [
            'sessionId'      => $sessionId,
            'conversationId' => $operatorHistory->getAttribute('conversation_id'),
            'history'        => TitanOperatorHistoryResource::make($operatorHistory)->jsonSerialize(),
        ]);
    }
}
