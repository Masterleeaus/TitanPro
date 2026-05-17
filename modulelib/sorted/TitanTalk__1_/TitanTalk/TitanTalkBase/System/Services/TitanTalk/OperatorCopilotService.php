<?php

declare(strict_types=1);

namespace App\Extensions\MarketingBot\System\Services\TitanTalk;

use App\Extensions\MarketingBot\System\Memory\ConversationContextBuilder;
use App\Extensions\MarketingBot\System\Models\MarketingConversation;
use App\Extensions\MarketingBot\System\Services\Conversation\AiChatbotService;
use App\Extensions\MarketingBot\System\Support\TitanTalkConfig;

class OperatorCopilotService
{
    public function __construct(protected AiChatbotService $aiChatbotService, protected ConversationContextBuilder $conversationContextBuilder)
    {
    }

    /**
     * @return array<string,mixed>
     */
    public function summarize(MarketingConversation $conversation): array
    {
        $messages = $conversation->histories()
            ->latest('id')
            ->limit(TitanTalkConfig::copilotSummaryWindow())
            ->get()
            ->reverse()
            ->values();

        $lines = [];
        foreach ($messages as $message) {
            $text = trim((string) $message->message);
            if ($text === '') {
                continue;
            }
            $lines[] = sprintf('%s: %s', $message->role ?: 'unknown', mb_substr($text, 0, 180));
        }

        $context = $this->conversationContextBuilder->build($conversation);

        return [
            'conversation_id' => $conversation->getKey(),
            'intent' => $conversation->intent,
            'goal' => $conversation->goal,
            'state' => $conversation->state,
            'role_pack' => $conversation->role_pack,
            'summary' => implode("\n", array_slice($lines, -12)),
            'message_count' => $messages->count(),
            'context_summary' => $context['summary'] ?? null,
            'context' => $context,
            'operator_actions' => ['ticket.create', 'booking.create', 'invoice.send_copy', 'human.handoff'],
        ];
    }

    /**
     * @return array<string,mixed>
     */
    public function suggestReply(MarketingConversation $conversation): array
    {
        $lastUserMessage = (string) optional($conversation->lastMessage()->first())->message;
        $summary = $this->summarize($conversation);

        $contextPrompt = trim(implode("\n", [
            'Operator copilot mode is active.',
            'Draft a human-sounding suggested reply for an operator.',
            'Do not claim actions happened unless confirmed.',
            'Conversation summary:',
            (string) ($summary['context_summary'] ?? ''),
            (string) $summary['summary'],
            'Latest user message:',
            $lastUserMessage,
        ]));

        $reply = $this->aiChatbotService->reply($conversation, $contextPrompt);

        $context = $this->conversationContextBuilder->build($conversation);

        return [
            'conversation_id' => $conversation->getKey(),
            'suggested_reply' => $reply,
            'summary' => $summary,
        ];
    }
}
