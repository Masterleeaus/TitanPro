<?php

declare(strict_types=1);

namespace App\Extensions\MarketingBot\System\Services\Conversation;

use App\Extensions\MarketingBot\System\Enums\ConversationIntent;

class IntentClassifierService
{
    /**
     * @param array<string,mixed> $context
     * @return array{intent: ConversationIntent, confidence: float, matched_rule: string, should_handoff?: bool, handoff_reason?: ?string, channel?: string}
     */
    public function classify(string $message, array $context = []): array
    {
        $normalized = mb_strtolower(trim($message));
        $channel = (string) ($context['channel'] ?? '');

        $rules = [
            ConversationIntent::HUMAN_HANDOFF => ['human', 'person', 'manager', 'agent', 'representative', 'someone real'],
            ConversationIntent::RESCHEDULE => ['reschedule', 'move booking', 'move appointment', 'different time', 'change time'],
            ConversationIntent::CANCEL => ['cancel', 'stop booking', "don't come", 'do not come', 'call it off'],
            ConversationIntent::INVOICE => ['invoice', 'payment', 'paid', 'receipt', 'bill', 'overdue', 'refund'],
            ConversationIntent::BOOKING => ['book', 'booking', 'appointment', 'availability', 'available tomorrow', 'come tomorrow'],
            ConversationIntent::QUOTE => ['quote', 'price', 'pricing', 'cost', 'how much', 'estimate'],
            ConversationIntent::COMPLAINT => ['complaint', 'not happy', 'bad service', 'unhappy', 'issue with service', 'terrible'],
            ConversationIntent::SUPPORT => ['help', 'support', 'problem', 'issue', 'not working', 'can you help'],
        ];

        foreach ($rules as $intent => $keywords) {
            foreach ($keywords as $keyword) {
                if (str_contains($normalized, $keyword)) {
                    $confidence = 0.9;
                    if (mb_strlen($normalized) > 180) {
                        $confidence = 0.82;
                    }

                    return [
                        'intent' => $intent,
                        'confidence' => $confidence,
                        'matched_rule' => $keyword,
                        'channel' => $channel,
                        'should_handoff' => in_array($intent, [ConversationIntent::HUMAN_HANDOFF, ConversationIntent::COMPLAINT], true),
                        'handoff_reason' => in_array($intent, [ConversationIntent::HUMAN_HANDOFF, ConversationIntent::COMPLAINT], true) ? 'Customer requested or implied human intervention.' : null,
                    ];
                }
            }
        }

        $shouldHandoff = mb_strlen($normalized) > 240;
        $confidence = $channel === 'voice' ? 0.5 : 0.45;

        return [
            'intent' => ConversationIntent::GENERAL,
            'confidence' => $confidence,
            'matched_rule' => 'fallback',
            'channel' => $channel,
            'should_handoff' => $shouldHandoff,
            'handoff_reason' => $shouldHandoff ? 'Long free-form message needs a human-quality review.' : null,
        ];
    }
}
