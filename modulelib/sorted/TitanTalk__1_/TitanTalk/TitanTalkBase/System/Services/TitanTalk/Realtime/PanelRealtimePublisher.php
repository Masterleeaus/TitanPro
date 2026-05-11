<?php

declare(strict_types=1);

namespace App\Extensions\MarketingBot\System\Services\TitanTalk\Realtime;

use App\Extensions\MarketingBot\System\Http\Resources\MarketingConversationResource;
use App\Extensions\MarketingBot\System\Http\Resources\MarketingMessageResource;
use App\Extensions\MarketingBot\System\Models\MarketingConversation;
use App\Extensions\MarketingBot\System\Models\MarketingMessageHistory;
use Throwable;

class PanelRealtimePublisher extends AblySupport
{
    public static function publishConversation(MarketingConversation $conversation, ?MarketingMessageHistory $history = null): void
    {
        $ably = static::ably();
        if (! $ably) {
            return;
        }

        try {
            $channel = $ably->channels->get(static::channelName('panel-' . $conversation->getAttribute('user_id')));
            $channel->publish('conversation', [
                'conversationId' => $conversation->getKey(),
                'conversation' => MarketingConversationResource::make($conversation->loadMissing('lastMessage'))->jsonSerialize(),
                'history' => $history ? MarketingMessageResource::make($history)->jsonSerialize() : null,
            ]);
        } catch (Throwable $e) {
            report($e);
        }
    }

    public static function publishUnreadCount(int $userId, int $count): void
    {
        $ably = static::ably();
        if (! $ably) {
            return;
        }

        try {
            $channel = $ably->channels->get(static::channelName('panel-' . $userId));
            $channel->publish('notification', [
                'count' => $count,
                'status' => 'success',
            ]);
        } catch (Throwable $e) {
            report($e);
        }
    }
}
