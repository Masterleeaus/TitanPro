<?php

namespace Modules\BookingModule\Actions\Bookings;

use Modules\BookingModule\Automation\BookingApprovalRuntime;
use Modules\BookingModule\Models\CleaningBooking;

class CreateBookingFromQuoteAcceptedAction
{
    public function __construct(protected BookingApprovalRuntime $approvals) {}

    /**
     * @param  array<string, mixed>  $quotePayload
     */
    public function execute(array $quotePayload): CleaningBooking
    {
        $booking = new CleaningBooking([
            'task_type' => 'booking',
            'heading' => (string) ($quotePayload['heading'] ?? 'Quote Booking'),
            'company_id' => (int) ($quotePayload['company_id'] ?? 0) ?: null,
            'project_id' => $quotePayload['project_id'] ?? null,
            'service_type' => $quotePayload['service_type'] ?? null,
            'service_address' => $quotePayload['service_address'] ?? null,
            'estimated_duration_hours' => $quotePayload['estimated_duration_hours'] ?? null,
            'booking_value' => $quotePayload['booking_value'] ?? null,
            'added_by' => $quotePayload['actor_id'] ?? null,
            'created_by' => $quotePayload['actor_id'] ?? null,
        ]);

        $booking->booking_status = $this->approvals->initialStatus($booking, $quotePayload);
        if ($booking->booking_status === 'pending_approval') {
            $booking->pending_approval_at = now();
            $booking->approval_due_at = now()->addHours($this->approvals->timeoutHours((int) ($booking->company_id ?? 0) ?: null));
        }

        $booking->save();

        return $booking;
    }
}
