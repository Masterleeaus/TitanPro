<?php

declare(strict_types=1);

namespace App\Extensions\MarketingBot\System\Services\TitanTalk\Channels;

use App\Extensions\MarketingBot\System\Models\MarketingConversation;
use App\Extensions\MarketingBot\System\Models\MarketingMessageHistory;

class ChannelMessageRecorder
{
    /**
     * @param array<string,mixed> $normalized
     */
    public function recordInbound(MarketingConversation $conversation, array $normalized): MarketingMessageHistory
    {
        return MarketingMessageHistory::query()->create([
            'conversation_id' => $conversation->getKey(),
            'message_id' => $normalized['message_id'] ?? null,
            'model' => null,
            'role' => 'user',
            'message' => $normalized['message'] ?? '',
            'type' => 'default',
            'message_type' => $normalized['message_type'] ?? 'text',
            'content_type' => 'text',
            'created_at' => now(),
        ]);
    }
}
