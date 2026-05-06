<?php

namespace Modules\BookingModule\Actions\Bookings;

use Modules\BookingModule\Models\CleaningBooking;

class CancelCleaningBookingAction
{
    public function __construct(protected TransitionCleaningBookingAction $transition) {}

    public function execute(CleaningBooking $booking, ?string $reason = null, array $payload = [], ?int $actorId = null): CleaningBooking
    {
        if ($reason !== null) {
            $payload['reason'] = $reason;
        }

        return $this->transition->execute($booking, 'cancelled', $payload, $actorId);
    }
}
