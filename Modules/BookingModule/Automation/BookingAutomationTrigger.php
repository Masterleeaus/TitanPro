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
        return ['booking.requested', 'booking.completed', 'booking.cancelled', 'schedule.assigned', 'schedule.rescheduled'];
    }
}
