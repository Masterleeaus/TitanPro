<?php

namespace Modules\BookingModule\Actions\Schedules;

use Illuminate\Support\Facades\Auth;
use Modules\BookingModule\Entities\Schedule;
use Modules\BookingModule\Events\ScheduleRescheduled;
use Modules\BookingModule\Jobs\SendBookingReminderJob;
use Modules\BookingModule\Services\ScheduleAssignmentService;

class RescheduleScheduleAction
{
    public function __construct(protected ScheduleAssignmentService $assignmentService) {}

    public function execute(Schedule $schedule, array $attributes, array $payload = [], ?int $actorId = null): Schedule
    {
        $oldWindow = [
            'date' => $schedule->date,
            'start_time' => $schedule->start_time,
            'end_time' => $schedule->end_time,
            'starts_at' => optional($schedule->starts_at)->toDateTimeString(),
            'ends_at' => optional($schedule->ends_at)->toDateTimeString(),
        ];

        $allowed = array_intersect_key($attributes, array_flip([
            'date', 'start_time', 'end_time', 'starts_at', 'ends_at', 'location', 'timezone', 'notes', 'status',
        ]));

        $schedule->fill($allowed);
        $schedule->save();

        $newWindow = [
            'date' => $schedule->date,
            'start_time' => $schedule->start_time,
            'end_time' => $schedule->end_time,
            'starts_at' => optional($schedule->starts_at)->toDateTimeString(),
            'ends_at' => optional($schedule->ends_at)->toDateTimeString(),
        ];

        event(new ScheduleRescheduled(
            $schedule,
            $oldWindow,
            $newWindow,
            (int) ($schedule->company_id ?? 0) ?: null,
            $actorId ?: (Auth::id() ?: null),
            $payload,
        ));

        if ($schedule->effective_assignee_id) {
            $this->assignmentService->dispatchRemindersForSchedule($schedule, SendBookingReminderJob::TRIGGER_RESCHEDULE);
        }

        return $schedule;
    }
}
