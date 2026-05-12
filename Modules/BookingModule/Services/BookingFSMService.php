<?php

namespace Modules\BookingModule\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use Modules\BookingModule\Models\CleaningBooking;

/**
 * BookingFSMService
 *
 * Validates and applies FSM status transitions for CleaningBooking records.
 *
 * Valid state machine:
 *   draft → confirmed → dispatched → in_progress → completed → invoiced → paid
 *   Alternate branches include pending_approval, rescheduled, no_show, cancelled
 */
class BookingFSMService
{
    /**
     * Attempt to transition $booking to $newStatus.
     *
     * @throws ValidationException  When the transition is not allowed.
     */
    public function transition(CleaningBooking $booking, string $newStatus, array $context = []): CleaningBooking
    {
        $this->assertValidTransition($booking, $newStatus);
        $this->assertGuardConditions($booking, $newStatus, $context);

        $booking->booking_status = $newStatus;

        if ($newStatus === 'pending_approval' && $booking->pending_approval_at === null) {
            $booking->pending_approval_at = now();
        }

        if (
            $newStatus === 'pending_approval'
            && $booking->approval_due_at === null
            && ! array_key_exists('approval_due_at', $context)
        ) {
            $booking->approval_due_at = now()->addHours((int) config('bookingmodule.automation.approval.timeout_hours', 24));
        }

        if ($newStatus === 'dispatched' && $booking->dispatched_at === null) {
            $booking->dispatched_at = now();
        }

        if ($newStatus === 'in_progress' && $booking->cleaner_arrived_at === null) {
            $booking->cleaner_arrived_at = now();
        }

        if ($newStatus === 'completed' && $booking->cleaner_departed_at === null) {
            $booking->cleaner_departed_at = now();
        }

        if ($newStatus === 'rescheduled' && $booking->rescheduled_at === null) {
            $booking->rescheduled_at = now();
        }

        if ($newStatus === 'no_show' && $booking->no_show_at === null) {
            $booking->no_show_at = now();
        }

        if ($newStatus === 'invoiced') {
            $booking->invoice_generated = true;
        }

        if ($newStatus === 'paid' && $booking->paid_at === null) {
            $booking->paid_at = now();
        }

        if (array_key_exists('approval_due_at', $context)) {
            $booking->approval_due_at = $context['approval_due_at'];
        }

        if (array_key_exists('job_card_completed_at', $context)) {
            $booking->job_card_completed_at = $context['job_card_completed_at'];
        }

        $booking->save();

        return $booking;
    }

    /**
     * Validate GPS coordinates (server-side — not just client-side).
     *
     * @throws ValidationException
     */
    public function validateCoordinates(?float $lat, ?float $lng): void
    {
        $validator = Validator::make(
            ['lat' => $lat, 'lng' => $lng],
            [
                'lat' => ['nullable', 'numeric', 'between:-90,90'],
                'lng' => ['nullable', 'numeric', 'between:-180,180'],
            ]
        );

        if ($validator->fails()) {
            throw ValidationException::withMessages($validator->errors()->toArray());
        }
    }

    /**
     * Assert that the requested transition is valid.
     *
     * @throws ValidationException
     */
    private function assertValidTransition(CleaningBooking $booking, string $newStatus): void
    {
        if (! $booking->canTransitionTo($newStatus)) {
            throw ValidationException::withMessages([
                'booking_status' => [
                    "Cannot transition from '{$booking->booking_status}' to '{$newStatus}'. "
                    . "Allowed: " . implode(', ', $booking->allowedNextStatuses() ?: ['none']),
                ],
            ]);
        }
    }

    /**
     * @throws ValidationException
     */
    private function assertGuardConditions(CleaningBooking $booking, string $newStatus, array $context): void
    {
        if ($booking->booking_status === 'confirmed' && $newStatus === 'dispatched') {
            if ($this->assignedTechniciansCount($booking, $context) < 1) {
                throw ValidationException::withMessages([
                    'booking_status' => ['Transition confirmed -> dispatched requires at least one assigned technician.'],
                ]);
            }
        }

        if ($booking->booking_status === 'completed' && $newStatus === 'invoiced') {
            if (! $this->hasJobCardCompletedEvidence($booking, $context)) {
                throw ValidationException::withMessages([
                    'booking_status' => ['Transition completed -> invoiced requires JobCardCompleted signal evidence.'],
                ]);
            }
        }
    }

    private function assignedTechniciansCount(CleaningBooking $booking, array $context): int
    {
        if (isset($context['assigned_technician_ids']) && is_array($context['assigned_technician_ids'])) {
            return count(array_filter($context['assigned_technician_ids']));
        }

        if (isset($context['assigned_technicians_count'])) {
            return max(0, (int) $context['assigned_technicians_count']);
        }

        if (method_exists($booking, 'assignedTechniciansCount')) {
            return max(0, (int) $booking->assignedTechniciansCount());
        }

        if (method_exists($booking, 'taskUsers')) {
            try {
                return (int) $booking->taskUsers()->count();
            } catch (\BadMethodCallException $e) {
                Log::debug('CleaningBooking taskUsers relation unavailable during guard check (model not persisted or relation not loaded).', [
                    'booking_id' => $booking->id ?? null,
                    'error' => $e->getMessage(),
                ]);
                return 0;
            }
        }

        return 0;
    }

    private function hasJobCardCompletedEvidence(CleaningBooking $booking, array $context): bool
    {
        return (bool) ($context['job_card_completed'] ?? false)
            || in_array('JobCardCompleted', (array) ($context['received_signals'] ?? []), true)
            || $booking->job_card_completed_at !== null;
    }
}
