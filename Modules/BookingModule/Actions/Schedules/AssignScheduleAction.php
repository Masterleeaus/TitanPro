<?php

namespace Modules\BookingModule\Actions\Schedules;

use Modules\BookingModule\Entities\Schedule;
use Modules\BookingModule\Services\ScheduleAssignmentService;

class AssignScheduleAction
{
    public function __construct(protected ScheduleAssignmentService $assignmentService) {}

    public function execute(Schedule $schedule, ?int $toUserId, ?string $note = null): Schedule
    {
        return $this->assignmentService->assign($schedule, $toUserId, $note);
    }
}
