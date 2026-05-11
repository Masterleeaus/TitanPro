<?php

declare(strict_types=1);

namespace App\Extensions\MarketingBot\System\Enums;

enum ConversationState: string
{
    case NEW = 'new';
    case QUALIFYING = 'qualifying';
    case ACTION_PENDING = 'action_pending';
    case AWAITING_CUSTOMER = 'awaiting_customer';
    case AWAITING_INTERNAL = 'awaiting_internal';
    case ESCALATED = 'escalated';
    case RESOLVED = 'resolved';
    case CLOSED = 'closed';

    public function isTerminal(): bool
    {
        return in_array($this, [self::RESOLVED, self::CLOSED], true);
    }
}
