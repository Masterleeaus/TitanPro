<?php

namespace Modules\TitanLeads\Services;

use Modules\TitanLeads\Models\MarketingConversation;
use Modules\TitanLeads\Models\MarketingMessageHistory;
use Illuminate\Support\Str;

class InboundIngestService
{
    /**
     * Normalize inbound events into ext_marketing_conversations + ext_marketing_message_histories
     * for Titan Leads mailbox.
     *
     * Expected keys: channel, from, body, user_id (optional)
     */
    public function ingest(array $event): void
    {
        $channel = $event['channel'] ?? 'unknown';
        $from    = $event['from'] ?? null;
        $body    = $event['body'] ?? '';
        $userId  = $event['user_id'] ?? null;

        if (!$from) {
            return;
        }

        $sessionId = Str::replaceFirst('+', '', (string) $from);

        $conversation = MarketingConversation::query()->firstOrCreate(
            [
                'type'       => $channel,
                'session_id' => $sessionId,
            ],
            [
                'user_id'            => $userId,
                'conversation_name'  => $sessionId,
                'customer_payload'   => ['from' => $from],
                'last_activity_at'   => now(),
            ]
        );

        $conversation->update(['last_activity_at' => now()]);

        MarketingMessageHistory::query()->create([
            'conversation_id' => $conversation->id,
            'message'         => $body,
            'message_id'      => (string) Str::uuid(),
            'role'            => 'user',
            'type'            => 'default',
            'message_type'    => 'text',
            'content_type'    => 'text',
        ]);
    }
}
