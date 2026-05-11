<?php

declare(strict_types=1);

namespace App\Extensions\MarketingBot\System\Conversation;

use App\Extensions\MarketingBot\System\Enums\ConversationIntent;
use App\Extensions\MarketingBot\System\Enums\ConversationState;

class ConversationStateMachine
{
    public function nextState(?string $currentState, ConversationIntent $intent, bool $hasToolPlan = false, bool $shouldHandoff = false): ConversationState
    {
        if ($shouldHandoff || $intent === ConversationIntent::HUMAN_HANDOFF) {
            return ConversationState::ESCALATED;
        }

        if ($hasToolPlan === true) {
            return ConversationState::ACTION_PENDING;
        }

        return match ($intent) {
            ConversationIntent::BOOKING,
            ConversationIntent::QUOTE => ConversationState::QUALIFYING,
            ConversationIntent::SUPPORT,
            ConversationIntent::COMPLAINT,
            ConversationIntent::INVOICE,
            ConversationIntent::RESCHEDULE,
            ConversationIntent::CANCEL => ConversationState::ACTION_PENDING,
            ConversationIntent::GENERAL => $currentState === ConversationState::AWAITING_INTERNAL->value ? ConversationState::AWAITING_INTERNAL : ConversationState::AWAITING_CUSTOMER,
            default => ConversationState::AWAITING_CUSTOMER,
        };
    }
}
