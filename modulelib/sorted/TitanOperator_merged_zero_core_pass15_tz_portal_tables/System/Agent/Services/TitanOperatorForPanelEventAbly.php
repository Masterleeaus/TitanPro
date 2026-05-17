<?php

namespace App\Extensions\TitanOperator\System\Agent\Services;

use App\Extensions\TitanOperator\System\Http\Resources\Admin\TitanOperatorConversationForAblyResource;
use App\Extensions\TitanOperator\System\Http\Resources\Api\TitanOperatorHistoryResource;
use App\Extensions\TitanOperator\System\Models\TitanOperator;
use App\Extensions\TitanOperator\System\Models\TitanOperatorConversation;
use App\Extensions\TitanOperator\System\Models\TitanOperatorHistory;
use App\Extensions\TitanOperator\System\Agent\Services\Contracts\AblyService;
use Exception;

class TitanOperatorForPanelEventAbly extends AblyService
{
    public static string $chanel = 'panel-conversation-';

    public static function dispatch(
        TitanOperator $titan_operator,
        TitanOperatorConversation $operatorConversation,
        ?TitanOperatorHistory $history = null,
    ): void {

        $apiKey = self::apiKey();

        if (! $apiKey) {
            return;
        }

        $ably = self::ablyRest();

        try {
            $channel = $ably->channels->get(
                self::$chanel . $titan_operator->getAttribute('user_id')
            );

            $channel->publish('conversation', [
                'userId'              => $titan_operator->getAttribute('user_id'),
                'conversationId'      => $operatorConversation->getKey(),
                'history'             => $history ? TitanOperatorHistoryResource::make($history)->jsonSerialize() : null,
                'operatorConversation' => TitanOperatorConversationForAblyResource::make($operatorConversation)->jsonSerialize(),
            ]);
        } catch (Exception $exception) {
            report($exception);
        }
    }
}
