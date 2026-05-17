<?php

declare(strict_types=1);

namespace App\Extensions\MarketingBot\System\Conversation;

use App\Extensions\MarketingBot\System\Enums\ConversationIntent;

class GoalEngine
{
    public function determine(ConversationIntent $intent): string
    {
        return match ($intent) {
            ConversationIntent::BOOKING => 'book_service',
            ConversationIntent::QUOTE => 'prepare_quote',
            ConversationIntent::SUPPORT => 'resolve_support_request',
            ConversationIntent::COMPLAINT => 'recover_service_issue',
            ConversationIntent::INVOICE => 'resolve_invoice_question',
            ConversationIntent::RESCHEDULE => 'reschedule_booking',
            ConversationIntent::CANCEL => 'cancel_booking',
            ConversationIntent::HUMAN_HANDOFF => 'handoff_to_human',
            ConversationIntent::GENERAL => 'answer_question',
        };
    }
}
