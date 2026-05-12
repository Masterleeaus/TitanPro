<?php

namespace Modules\BookingModule\Listeners;

use Modules\BookingModule\Events\BookingApprovalDecided;
use Modules\BookingModule\Events\BookingApprovalRequested;
use Modules\BookingModule\Events\BookingCancelled;
use Modules\BookingModule\Events\BookingCompleted;

class EmitBookingLifecycleSignals
{
    public function handle(object $event): void
    {
        if (! class_exists(\App\Extensions\TitanPulse\Services\SignalBus\SignalEmitter::class)) {
            return;
        }

        if ($event instanceof BookingCompleted) {
            $this->emit(
                'EInvoice.DraftInvoice',
                $event->booking->id,
                (int) ($event->booking->company_id ?? 0) ?: null,
                ['booking_id' => $event->booking->id]
            );
            $this->emit(
                'CleanQuality.TriggerInspection',
                $event->booking->id,
                (int) ($event->booking->company_id ?? 0) ?: null,
                ['booking_id' => $event->booking->id]
            );
        }

        if ($event instanceof BookingCancelled) {
            $this->emit(
                'ZeroFussPortal.NotifyCustomer',
                $event->booking->id,
                (int) ($event->booking->company_id ?? 0) ?: null,
                ['booking_id' => $event->booking->id, 'reason' => $event->reason]
            );
        }

        if ($event instanceof BookingApprovalRequested) {
            $this->emit(
                'booking.approval.requested',
                $event->booking->id,
                (int) ($event->booking->company_id ?? 0) ?: null,
                ['booking_id' => $event->booking->id, 'approval_due_at' => $event->booking->approval_due_at]
            );
        }

        if ($event instanceof BookingApprovalDecided) {
            $this->emit(
                'booking.approval.decided',
                $event->booking->id,
                (int) ($event->booking->company_id ?? 0) ?: null,
                ['booking_id' => $event->booking->id, 'approved' => $event->approved, 'reason' => $event->reason]
            );
        }
    }

    private function emit(string $signalType, ?int $bookingId, ?int $companyId, array $payload = []): void
    {
        if (! $companyId || ! $bookingId) {
            return;
        }

        try {
            \App\Extensions\TitanPulse\Services\SignalBus\SignalEmitter::emit(
                $signalType,
                'cleaning_booking',
                $bookingId,
                $payload,
                [
                    'team_id' => $companyId,
                    'company_id' => $companyId,
                    'source' => 'bookingmodule.lifecycle',
                ],
            );
        } catch (\Throwable) {
            // Non-blocking signal bridge.
        }
    }
}
