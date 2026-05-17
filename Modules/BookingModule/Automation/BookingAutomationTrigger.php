<?php

namespace Modules\BookingModule\Automation;

class BookingAutomationTrigger
{
    public function name(): string
    {
        return 'booking.lifecycle';
    }

    public function events(): array
    {
        return [
            'booking.requested',
            'booking.completed',
            'booking.cancelled',
            'booking.approval.requested',
            'booking.approval.decided',
            'QuoteEngine.QuoteAccepted',
            'schedule.assigned',
            'schedule.rescheduled',
        ];
    }
}
