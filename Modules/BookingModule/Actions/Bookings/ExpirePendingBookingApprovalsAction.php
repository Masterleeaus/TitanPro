<?php

namespace Modules\BookingModule\Actions\Bookings;

use Modules\BookingModule\Models\CleaningBooking;

class ExpirePendingBookingApprovalsAction
{
    public function __construct(protected TransitionCleaningBookingAction $transition) {}

    public function execute(?int $companyId = null): int
    {
        $query = CleaningBooking::query()
            ->where('booking_status', 'pending_approval')
            ->whereNotNull('approval_due_at')
            ->where('approval_due_at', '<=', now());

        if ($companyId !== null) {
            $query->where('company_id', $companyId);
        }

        $count = 0;
        foreach ($query->cursor() as $booking) {
            $this->transition->execute($booking, 'cancelled', [
                'approval_timeout' => true,
                'approval_decision' => 'denied',
                'reason' => 'approval_timeout',
            ]);
            $count++;
        }

        return $count;
    }
}

