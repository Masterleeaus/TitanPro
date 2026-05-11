<?php

declare(strict_types=1);

namespace App\Extensions\MarketingBot\System\Memory;

use App\Extensions\MarketingBot\System\Models\MarketingConversation;
use App\Extensions\MarketingBot\System\Models\TitanTalk\PersistentMemory;

class PersistentMemoryService
{
    /**
     * @return array<string,mixed>
     */
    public function recall(MarketingConversation $conversation): array
    {
        return PersistentMemory::query()
            ->where('conversation_id', $conversation->getKey())
            ->get()
            ->mapWithKeys(static fn (PersistentMemory $memory): array => [
                (string) $memory->memory_key => $memory->memory_value ?? [],
            ])
            ->toArray();
    }

    /**
     * @param array<string,mixed> $classification
     * @param array<string,mixed> $context
     * @param array<string,mixed> $workflow
     */
    public function remember(MarketingConversation $conversation, array $classification, array $context, array $workflow = []): void
    {
        $entries = [
            'last_intent' => [
                'intent' => (string) ($conversation->intent ?? ($classification['intent']->value ?? 'general')),
                'confidence' => (float) ($classification['confidence'] ?? 0),
                'goal' => (string) ($classification['goal'] ?? $conversation->goal ?? 'answer_question'),
                'state' => (string) ($classification['next_state'] ?? $conversation->state ?? 'new'),
            ],
            'last_entities' => (array) ($classification['entities'] ?? []),
            'summary' => [
                'summary' => (string) ($context['summary'] ?? ''),
                'recent_user_messages' => (array) ($context['recent_user_messages'] ?? []),
            ],
        ];

        if ($workflow !== []) {
            $entries['last_workflow'] = $workflow;
        }

        foreach ($entries as $key => $value) {
            PersistentMemory::query()->updateOrCreate(
                [
                    'conversation_id' => $conversation->getKey(),
                    'memory_key' => $key,
                ],
                [
                    'user_id' => $conversation->user_id,
                    'channel' => $conversation->type,
                    'memory_value' => is_array($value) ? $value : ['value' => $value],
                    'last_seen_at' => now(),
                ]
            );
        }
    }
}
