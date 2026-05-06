<?php

namespace Modules\BookingModule\Listeners;

use Modules\BookingModule\Events\BookingCancelled;
use Modules\BookingModule\Events\BookingCompleted;
use Modules\BookingModule\Events\ScheduleAssigned;
use Modules\BookingModule\Events\ScheduleRescheduled;
use Modules\BookingModule\Jobs\SendBookingLifecycleMailJob;

class SendBookingLifecycleMail
{
    public function handle(object $event): void
    {
        if ($event instanceof BookingCompleted) {
            $email = $event->booking->email ?? $event->booking->customer?->email ?? null;
            if ($email) {
                SendBookingLifecycleMailJob::dispatch(
                    $email,
                    __('Booking completed'),
                    __('Your booking has been completed.'),
                    $this->bookingDetails($event->booking) + ['Status' => 'completed'],
                    null,
                    __('View booking')
                )->onQueue('notifications');
            }
            return;
        }

        if ($event instanceof BookingCancelled) {
            $email = $event->booking->email ?? $event->booking->customer?->email ?? null;
            if ($email) {
                SendBookingLifecycleMailJob::dispatch(
                    $email,
                    __('Booking cancelled'),
                    __('Your booking has been cancelled.'),
                    $this->bookingDetails($event->booking) + ['Reason' => $event->reason ?: __('Not provided')],
                    null,
                    __('View booking')
                )->onQueue('notifications');
            }
            return;
        }

        if ($event instanceof ScheduleAssigned && $event->toUserId && $event->schedule->assignee?->email) {
            SendBookingLifecycleMailJob::dispatch(
                $event->schedule->assignee->email,
                __('Schedule assigned'),
                __('A schedule has been assigned to you.'),
                $this->scheduleDetails($event->schedule),
                null,
                __('View schedule')
            )->onQueue('notifications');
            return;
        }

        if ($event instanceof ScheduleRescheduled && $event->schedule->assignee?->email) {
            SendBookingLifecycleMailJob::dispatch(
                $event->schedule->assignee->email,
                __('Schedule rescheduled'),
                __('One of your assigned schedules has been rescheduled.'),
                $this->scheduleDetails($event->schedule) + [
                    'Previous' => json_encode($event->oldWindow),
                    'Updated' => json_encode($event->newWindow),
                ],
                null,
                __('View schedule')
            )->onQueue('notifications');
        }
    }

    private function bookingDetails(object $booking): array
    {
        return array_filter([
            'Booking ID' => $booking->id ?? null,
            'Service' => $booking->service_type ?? null,
            'Address' => $booking->service_address ?? null,
        ]);
    }

    private function scheduleDetails(object $schedule): array
    {
        return array_filter([
            'Schedule ID' => $schedule->id ?? null,
            'Date' => $schedule->date ?? null,
            'Start time' => $schedule->start_time ?? null,
            'Location' => $schedule->location ?? null,
        ]);
    }
}
