<?php

namespace Modules\BookingModule\Listeners;

use Illuminate\Database\Eloquent\Model;
use Modules\BookingModule\Events\BookingCancelled;
use Modules\BookingModule\Events\BookingCompleted;
use Modules\BookingModule\Events\BookingStatusChanged;
use Modules\BookingModule\Events\ScheduleAssigned;
use Modules\BookingModule\Events\ScheduleRescheduled;
use Modules\BookingModule\Jobs\RecordBookingLifecycleLogJob;

class RecordBookingLifecycleLog
{
    public function handle(object $event): void
    {
        if ($event instanceof BookingCompleted) {
            $this->dispatchForModel(
                $event->booking,
                'booking.completed',
                null,
                'completed',
                [],
                null,
            );
            return;
        }

        if ($event instanceof BookingStatusChanged) {
            $this->dispatchForModel(
                $event->booking,
                'booking.status_changed',
                $event->fromStatus,
                $event->toStatus,
                $event->payload,
                $event->actorId,
            );
            return;
        }

        if ($event instanceof BookingCancelled) {
            $this->dispatchForModel(
                $event->booking,
                'booking.cancelled',
                null,
                'cancelled',
                $event->payload + ['reason' => $event->reason],
                $event->actorId,
            );
            return;
        }

        if ($event instanceof ScheduleAssigned) {
            $this->dispatchForModel(
                $event->schedule,
                'schedule.assigned',
                $event->fromUserId ? (string) $event->fromUserId : null,
                $event->toUserId ? (string) $event->toUserId : null,
                $event->payload + [
                    'from_user_id' => $event->fromUserId,
                    'to_user_id' => $event->toUserId,
                ],
                $event->actorId,
            );
            return;
        }

        if ($event instanceof ScheduleRescheduled) {
            $this->dispatchForModel(
                $event->schedule,
                'schedule.rescheduled',
                null,
                null,
                $event->payload + [
                    'old_window' => $event->oldWindow,
                    'new_window' => $event->newWindow,
                ],
                $event->actorId,
            );
        }
    }

    private function dispatchForModel(
        Model $subject,
        string $event,
        ?string $fromStatus = null,
        ?string $toStatus = null,
        array $payload = [],
        ?int $actorId = null,
    ): void {
        RecordBookingLifecycleLogJob::dispatch(
            $subject::class,
            (int) $subject->getKey(),
            $event,
            (int) ($subject->company_id ?? 0) ?: null,
            $fromStatus,
            $toStatus,
            $payload,
            $actorId,
        )->onQueue('default');
    }
}
