<?php

namespace Modules\BookingModule\Actions\Bookings;

use Illuminate\Support\Facades\Auth;
use Modules\BookingModule\Events\BookingCancelled;
use Modules\BookingModule\Events\BookingCompleted;
use Modules\BookingModule\Events\BookingStatusChanged;
use Modules\BookingModule\Models\CleaningBooking;
use Modules\BookingModule\Services\BookingFSMService;

class TransitionCleaningBookingAction
{
    public function __construct(protected BookingFSMService $fsm) {}

    public function execute(CleaningBooking $booking, string $newStatus, array $payload = [], ?int $actorId = null): CleaningBooking
    {
        $fromStatus = $booking->booking_status;
        $booking = $this->fsm->transition($booking, $newStatus);
        $actorId = $actorId ?: (Auth::id() ?: null);
        $companyId = (int) ($booking->company_id ?? 0) ?: null;

        event(new BookingStatusChanged($booking, $fromStatus, $newStatus, $companyId, $actorId, $payload));

        if ($newStatus === 'completed') {
            event(new BookingCompleted($booking));
        }

        if ($newStatus === 'cancelled') {
            event(new BookingCancelled(
                $booking,
                $companyId,
                $actorId,
                $payload['reason'] ?? null,
                $payload,
            ));
        }

        return $booking;
    }
}
