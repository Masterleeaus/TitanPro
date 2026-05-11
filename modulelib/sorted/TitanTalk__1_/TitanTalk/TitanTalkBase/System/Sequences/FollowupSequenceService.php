<?php

declare(strict_types=1);

namespace App\Extensions\MarketingBot\System\Sequences;

use App\Extensions\MarketingBot\System\Models\MarketingConversation;

class FollowupSequenceService
{
    /** @param array<string,mixed> $classification
     *  @return array<string,mixed>
     */
    public function suggest(MarketingConversation $conversation, array $classification = []): array
    {
        $goal = (string) ($classification['goal'] ?? $conversation->goal ?? 'answer_question');
        $state = (string) ($classification['next_state'] ?? $conversation->state ?? 'new');

        $sequence = match ($goal) {
            'book_service' => ['ask_scope', 'offer_slots', 'confirm_booking'],
            'collect_payment' => ['confirm_invoice', 'share_copy', 'offer_human_help'],
            'resolve_support' => ['acknowledge_issue', 'gather_details', 'create_ticket'],
            'qualify_lead' => ['capture_contact', 'capture_scope', 'prepare_quote'],
            default => ['answer_question', 'offer_handoff'],
        };

        return [
            'goal' => $goal,
            'state' => $state,
            'sequence' => $sequence,
            'next_step' => $sequence[0] ?? 'answer_question',
            'channel_fit' => (string) $conversation->type,
        ];
    }
}
