<?php

declare(strict_types=1);

namespace App\Extensions\MarketingBot\System\Services\TitanTalk\Realtime;

use App\Extensions\MarketingBot\System\Http\Resources\MarketingMessageResource;
use App\Extensions\MarketingBot\System\Models\MarketingMessageHistory;
use Throwable;

class SessionRealtimePublisher extends AblySupport
{
    public static function publishMessage(MarketingMessageHistory $history, string $sessionId): void
    {
        $ably = static::ably();
        if (! $ably) {
            return;
        }

        try {
            $channel = $ably->channels->get(static::channelName('session-' . $sessionId));
            $channel->publish('new-message', [
                'sessionId' => $sessionId,
                'conversationId' => $history->getAttribute('conversation_id'),
                'history' => MarketingMessageResource::make($history)->jsonSerialize(),
            ]);
        } catch (Throwable $e) {
            report($e);
        }
    }
}
