<?php

namespace Modules\Biometric\Listeners;

use Illuminate\Support\Facades\Event;
use Modules\Biometric\Events\AttendanceRecorded;
use Modules\Biometric\Events\AttendanceSyncedToHr;

class UpdateHrAttendanceListener
{
    public function handle(AttendanceRecorded $event): void
    {
        Event::dispatch('HRCore.BiometricAttendanceRecorded', [
            'company_id' => $event->companyId,
            'employee_id' => $event->employeeId,
            'user_id' => $event->userId,
            'occurred_at' => $event->occurredAt->format(DATE_ATOM),
            'clock_in' => $event->clockIn,
        ]);

        event(new AttendanceSyncedToHr(
            companyId: $event->companyId,
            employeeId: $event->employeeId,
            userId: $event->userId,
        ));
    }
}

