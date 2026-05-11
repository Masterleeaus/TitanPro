<?php

declare(strict_types=1);

namespace App\Extensions\MarketingBot\System\Signals;

use App\Extensions\MarketingBot\System\Models\MarketingConversation;
use Illuminate\Support\Facades\Log;

class TitanTalkSignalBridge
{
    /**
     * @param array<string,mixed> $command
     * @return array<string,mixed>
     */
    public function emitFromCommand(MarketingConversation $conversation, array $command, string $channel = 'chat'): array
    {
        $signal = [
            'signal' => $this->mapSignal((string) ($command['command'] ?? 'knowledge.answer')),
            'source' => 'titantalk',
            'channel' => $channel,
            'conversation_id' => $conversation->getKey(),
            'conversation_state' => $conversation->state,
            'goal' => $conversation->goal,
            'role_pack' => $conversation->role_pack,
            'command' => $command['command'] ?? null,
            'params' => $command['params'] ?? [],
            'requires_confirmation' => (bool) ($command['requires_confirmation'] ?? false),
            'is_actionable' => (bool) ($command['is_actionable'] ?? false),
            'emitted_at' => now()->toIso8601String(),
        ];

        Log::info('TitanTalk signal bridge emitted signal', $signal);

        return $signal;
    }

    private function mapSignal(string $command): string
    {
        return match ($command) {
            'booking.create' => 'booking.requested',
            'booking.reschedule' => 'booking.reschedule_requested',
            'booking.cancel' => 'booking.cancel_requested',
            'quote.prepare' => 'quote.requested',
            'ticket.create' => 'support.requested',
            'service.issue.log' => 'service.issue_reported',
            'invoice.lookup' => 'invoice.lookup_requested',
            'human.handoff' => 'conversation.handoff_requested',
            default => 'conversation.answered',
        };
    }
}
