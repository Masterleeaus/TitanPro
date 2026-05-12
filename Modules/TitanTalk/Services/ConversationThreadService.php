<?php

namespace Modules\TitanTalk\Services;

use Modules\TitanTalk\Models\Conversation;
use Modules\TitanTalk\Models\Message;

class ConversationThreadService
{
    public function recordInbound(string $channel, string $externalRef, string $text, array $metadata = [], ?int $tenantId = null): Conversation
    {
        $conversation = Conversation::firstOrCreate(
            ['channel' => $channel, 'external_ref' => $externalRef],
            ['tenant_id' => $tenantId]
        );

        Message::create([
            'tenant_id' => $tenantId,
            'conversation_id' => $conversation->id,
            'sender' => 'user',
            'text' => $text,
            'meta' => $metadata,
        ]);

        return $conversation;
    }

    public function recordOutbound(Conversation $conversation, string $text, array $metadata = [], ?int $tenantId = null): Message
    {
        return Message::create([
            'tenant_id' => $tenantId ?? $conversation->tenant_id,
            'conversation_id' => $conversation->id,
            'sender' => 'bot',
            'text' => $text,
            'meta' => $metadata,
        ]);
    }
}

