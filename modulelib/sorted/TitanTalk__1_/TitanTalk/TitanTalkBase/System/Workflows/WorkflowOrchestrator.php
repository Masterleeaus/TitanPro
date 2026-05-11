<?php

declare(strict_types=1);

namespace App\Extensions\MarketingBot\System\Workflows;

use App\Extensions\MarketingBot\System\Models\MarketingConversation;

class WorkflowOrchestrator
{
    /**
     * @param array<string,mixed> $classification
     * @return array<string,mixed>
     */
    public function build(MarketingConversation $conversation, array $classification): array
    {
        $goal = (string) ($classification['goal'] ?? $conversation->goal ?? 'answer_question');
        $command = (string) (($classification['command']['command'] ?? '') ?: 'knowledge.answer');
        $steps = match ($goal) {
            'book_service' => ['lead.capture', 'availability.check', 'booking.confirm'],
            'resolve_support' => ['issue.capture', 'ticket.create', 'followup.schedule'],
            'collect_payment' => ['invoice.lookup', 'payment.explain', 'handoff_if_disputed'],
            'qualify_lead' => ['contact.capture', 'scope.qualify', 'quote.prepare'],
            default => ['knowledge.answer'],
        };

        return [
            'goal' => $goal,
            'command' => $command,
            'workflow_key' => $goal . ':' . $command,
            'steps' => $steps,
            'status' => $classification['safety']['safe_to_autorun'] ?? false ? 'ready' : 'review',
            'current_step' => $steps[0] ?? null,
            'created_at' => now()->toIso8601String(),
        ];
    }
}
