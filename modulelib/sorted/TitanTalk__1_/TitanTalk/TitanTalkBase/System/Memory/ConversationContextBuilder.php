<?php

declare(strict_types=1);

namespace App\Extensions\MarketingBot\System\Memory;

use App\Extensions\MarketingBot\System\Models\MarketingConversation;
use App\Extensions\MarketingBot\System\Support\TitanTalkConfig;

class ConversationContextBuilder
{
    public function __construct(protected PersistentMemoryService $persistentMemoryService)
    {
    }
    /**
     * @return array<string,mixed>
     */
    public function build(MarketingConversation $conversation): array
    {
        $history = $conversation->histories()
            ->latest('id')
            ->limit(TitanTalkConfig::contextHistoryWindow())
            ->get()
            ->reverse()
            ->values();

        $messages = [];
        $recentUserMessages = [];
        $recentAssistantMessages = [];

        foreach ($history as $item) {
            $text = trim((string) $item->message);
            if ($text === '') {
                continue;
            }

            $entry = [
                'role' => (string) ($item->role ?: 'unknown'),
                'message' => mb_substr($text, 0, 280),
                'created_at' => optional($item->created_at)->toIso8601String(),
            ];

            $messages[] = $entry;

            if ($entry['role'] === 'user') {
                $recentUserMessages[] = $entry['message'];
            }

            if ($entry['role'] === 'assistant') {
                $recentAssistantMessages[] = $entry['message'];
            }
        }

        $payload = (array) ($conversation->customer_payload ?? []);
        $lastEntities = (array) ($payload['last_entities'] ?? []);
        $lastToolPlans = (array) ($payload['last_tool_plans'] ?? []);
        $lastSignal = (array) ($payload['last_signal'] ?? []);
        $activeProcesses = (array) ($payload['active_processes'] ?? []);

        $persistentMemory = $this->persistentMemoryService->recall($conversation);

        $summary = trim(implode(' | ', array_filter([
            'intent=' . ($conversation->intent ?: 'unknown'),
            'goal=' . ($conversation->goal ?: 'answer_question'),
            'state=' . ($conversation->state ?: 'new'),
            'role=' . ($conversation->role_pack ?: TitanTalkConfig::defaultRolePack()),
            'user_msgs=' . count($recentUserMessages),
            'assistant_msgs=' . count($recentAssistantMessages),
            $lastSignal !== [] ? 'last_signal=' . (string) ($lastSignal['signal'] ?? 'unknown') : null,
        ])));

        return [
            'conversation_id' => $conversation->getKey(),
            'conversation_name' => $conversation->conversation_name,
            'channel' => (string) $conversation->type,
            'intent' => $conversation->intent,
            'goal' => $conversation->goal,
            'state' => $conversation->state,
            'role_pack' => $conversation->role_pack ?: TitanTalkConfig::defaultRolePack(),
            'last_entities' => $lastEntities,
            'last_tool_plans' => $lastToolPlans,
            'last_signal' => $lastSignal,
            'active_processes' => $activeProcesses,
            'message_count' => count($messages),
            'recent_messages' => $messages,
            'recent_user_messages' => array_slice($recentUserMessages, -3),
            'recent_assistant_messages' => array_slice($recentAssistantMessages, -3),
            'persistent_memory' => $persistentMemory,
            'summary' => $summary,
        ];
    }

    public function asSystemContext(MarketingConversation $conversation): string
    {
        $context = $this->build($conversation);

        $lines = [
            'TitanTalk conversation context snapshot:',
            'Summary: ' . (string) ($context['summary'] ?? 'n/a'),
            'Recent user messages: ' . implode(' || ', (array) ($context['recent_user_messages'] ?? [])),
            'Recent assistant messages: ' . implode(' || ', (array) ($context['recent_assistant_messages'] ?? [])),
            'Last known entities: ' . json_encode($context['last_entities'] ?? [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            'Last planned tools: ' . json_encode($context['last_tool_plans'] ?? [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
        ];

        if (! empty($context['last_signal'])) {
            $lines[] = 'Last TitanPulse signal: ' . json_encode($context['last_signal'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }

        if (! empty($context['active_processes'])) {
            $lines[] = 'Active TitanPulse processes: ' . json_encode($context['active_processes'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }

        if (! empty($context['persistent_memory'])) {
            $lines[] = 'Persistent memory: ' . json_encode($context['persistent_memory'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }

        return implode("\n", $lines);
    }
}
