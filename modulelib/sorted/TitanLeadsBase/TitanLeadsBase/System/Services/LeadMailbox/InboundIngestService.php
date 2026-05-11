<?php

namespace App\Extensions\TitanLeads\System\Services\LeadMailbox;

use App\Extensions\TitanLeads\System\Models\MarketingConversation;
use App\Extensions\TitanLeads\System\Models\MarketingMessageHistory;
use Illuminate\Support\Str;

class InboundIngestService
{
    /**
     * Normalize inbound events into ext_marketing_conversations + ext_marketing_message_histories
     * for Titan Leads mailbox.
     *
     * Expected keys: channel, from, to, body
     */
    public static function ingest(array $event): void
    {
        $channel = $event['channel'] ?? 'unknown';
        $from = $event['from'] ?? null;
        $to = $event['to'] ?? null;
        $body = $event['body'] ?? '';

        if (!$from) {
            return;
        }

        $conversation = MarketingConversation::query()->firstOrCreate([
            'type' => $channel,
            'to_id' => $to,
            'from_id' => $from,
        ], [
            'status' => 'open',
        ]);

        MarketingMessageHistory::query()->create([
            'conversation_id' => $conversation->id,
            'message' => $body,
            'message_id' => (string) Str::uuid(),
            'direction' => 'inbound',
        ]);
    }
}
