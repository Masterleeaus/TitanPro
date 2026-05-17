<?php

namespace Modules\BookingModule\Actions\Bookings;

use Modules\BookingModule\Models\CleaningBooking;

class ReviewBookingApprovalAction
{
    public function __construct(protected TransitionCleaningBookingAction $transition) {}

    public function execute(CleaningBooking $booking, bool $approved, ?string $reason = null, array $payload = [], ?int $actorId = null): CleaningBooking
    {
        $payload['approval_reviewed'] = true;
        $payload['approval_decision'] = $approved ? 'approved' : 'denied';
        if ($reason !== null) {
            $payload['reason'] = $reason;
        }

        return $this->transition->execute(
            $booking,
            $approved ? 'confirmed' : 'cancelled',
            $payload,
            $actorId,
        );
    }
}
