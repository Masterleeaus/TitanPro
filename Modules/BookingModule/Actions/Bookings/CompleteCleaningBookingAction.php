<?php

namespace Modules\BookingModule\Actions\Bookings;

use Modules\BookingModule\Models\CleaningBooking;

class CompleteCleaningBookingAction
{
    public function __construct(protected TransitionCleaningBookingAction $transition) {}

    public function execute(CleaningBooking $booking, array $payload = [], ?int $actorId = null): CleaningBooking
    {
        return $this->transition->execute($booking, 'completed', $payload, $actorId);
    }
}
