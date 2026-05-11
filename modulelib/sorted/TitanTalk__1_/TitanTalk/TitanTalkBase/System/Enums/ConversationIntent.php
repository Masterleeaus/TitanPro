<?php

declare(strict_types=1);

namespace App\Extensions\MarketingBot\System\Enums;

enum ConversationIntent: string
{
    case BOOKING = 'booking';
    case QUOTE = 'quote';
    case SUPPORT = 'support';
    case COMPLAINT = 'complaint';
    case INVOICE = 'invoice';
    case RESCHEDULE = 'reschedule';
    case CANCEL = 'cancel';
    case HUMAN_HANDOFF = 'human_handoff';
    case GENERAL = 'general';

    public function label(): string
    {
        return match ($this) {
            self::BOOKING => 'Booking Request',
            self::QUOTE => 'Quote Request',
            self::SUPPORT => 'Support',
            self::COMPLAINT => 'Complaint',
            self::INVOICE => 'Invoice / Payment',
            self::RESCHEDULE => 'Reschedule',
            self::CANCEL => 'Cancellation',
            self::HUMAN_HANDOFF => 'Human Handoff',
            self::GENERAL => 'General Question',
        };
    }
}
