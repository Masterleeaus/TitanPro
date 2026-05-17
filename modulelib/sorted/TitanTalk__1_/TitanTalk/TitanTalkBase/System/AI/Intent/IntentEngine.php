<?php

declare(strict_types=1);

namespace App\Extensions\MarketingBot\System\AI\Intent;

use App\Extensions\MarketingBot\System\Enums\ConversationIntent;
use App\Extensions\MarketingBot\System\Services\Conversation\IntentClassifierService;

class IntentEngine
{
    public function __construct(
        protected IntentClassifierService $intentClassifierService,
    ) {}

    /**
     * @param array<string,mixed> $context
     * @return array{intent: ConversationIntent, confidence: float, matched_rule: string, should_handoff?: bool, handoff_reason?: ?string, channel?: string, entities: array<string,mixed>}
     */
    public function detect(string $message, array $context = []): array
    {
        $classification = $this->intentClassifierService->classify($message, $context);
        $classification['entities'] = $this->extractEntities($message, $classification['intent']);

        return $classification;
    }

    /**
     * @return array<string,mixed>
     */
    protected function extractEntities(string $message, ConversationIntent $intent): array
    {
        $entities = [];

        if (preg_match('/\b(\d{1,2})(?::(\d{2}))?\s?(am|pm)\b/i', $message, $match) === 1) {
            $entities['time_hint'] = strtolower($match[0]);
        }

        if (preg_match('/\b(today|tomorrow|monday|tuesday|wednesday|thursday|friday|saturday|sunday)\b/i', $message, $match) === 1) {
            $entities['day_hint'] = strtolower($match[1]);
        }

        if (preg_match('/\$\s?([0-9]+(?:\.[0-9]{2})?)/', $message, $match) === 1) {
            $entities['amount_hint'] = $match[1];
        }

        if ($intent === ConversationIntent::INVOICE && preg_match('/\b(invoice|inv)[\s#:-]*([a-z0-9-]+)/i', $message, $match) === 1) {
            $entities['invoice_reference'] = $match[2];
        }

        return $entities;
    }
}
