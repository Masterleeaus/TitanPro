<?php

namespace Modules\BookingModule\Actions\Bookings;

use Illuminate\Support\Facades\Auth;
use Modules\BookingModule\Automation\BookingApprovalRuntime;
use Modules\BookingModule\Events\BookingApprovalDecided;
use Modules\BookingModule\Events\BookingApprovalRequested;
use Modules\BookingModule\Events\BookingCancelled;
use Modules\BookingModule\Events\BookingCompleted;
use Modules\BookingModule\Events\BookingStatusChanged;
use Modules\BookingModule\Models\CleaningBooking;
use Modules\BookingModule\Services\BookingFSMService;

class TransitionCleaningBookingAction
{
    public function __construct(
        protected BookingFSMService $fsm,
        protected BookingApprovalRuntime $approvals,
    ) {}

    /**
     * Execute a booking transition.
     *
     * Note: when approval is required for draft->confirmed transitions, the effective
     * transition is redirected to pending_approval.
     */
    public function execute(CleaningBooking $booking, string $newStatus, array $payload = [], ?int $actorId = null): CleaningBooking
    {
        $fromStatus = (string) $booking->booking_status;
        $actorId = $actorId ?: (Auth::id() ?: null);
        $companyId = (int) ($booking->company_id ?? 0) ?: null;

        if (
            $fromStatus === 'draft'
            && $newStatus === 'confirmed'
            && ! (($payload['skip_approval'] ?? false) === true)
            && $this->approvals->requiresApproval($booking, $payload)
        ) {
            $newStatus = 'pending_approval';
            $payload['approval_required'] = true;
            $payload['approval_timeout_hours'] = $this->approvals->timeoutHours($companyId);
            $payload['approval_due_at'] = now()->addHours((int) $payload['approval_timeout_hours']);
        }

        $booking = $this->fsm->transition($booking, $newStatus, $payload);

        if ($fromStatus === 'pending_approval' && in_array($newStatus, ['confirmed', 'cancelled'], true)) {
            $booking->approval_decision_at = now();
            $booking->approval_decision_reason = $payload['reason'] ?? null;
            $booking->save();
        }

        event(new BookingStatusChanged($booking, $fromStatus, $newStatus, $companyId, $actorId, $payload));

        if ($newStatus === 'pending_approval') {
            event(new BookingApprovalRequested(
                $booking,
                $companyId,
                $actorId,
                $payload['reason'] ?? null,
                $payload,
            ));
        }

        if ($fromStatus === 'pending_approval' && in_array($newStatus, ['confirmed', 'cancelled'], true)) {
            event(new BookingApprovalDecided(
                $booking,
                $newStatus === 'confirmed',
                $companyId,
                $actorId,
                $payload['reason'] ?? null,
                $payload,
            ));
        }

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
