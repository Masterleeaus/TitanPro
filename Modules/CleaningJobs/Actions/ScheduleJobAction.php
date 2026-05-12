<?php

namespace Modules\CleaningJobs\Actions;

use DateTimeInterface;
use Modules\CleaningJobs\Events\JobScheduled;
use Modules\CleaningJobs\Models\WorkOrder;

class ScheduleJobAction
{
    public function execute(WorkOrder $workOrder, DateTimeInterface $scheduledFor): WorkOrder
    {
        $workOrder->scheduled_for = $scheduledFor;
        $workOrder->status = 'scheduled';
        $workOrder->save();
        event(new JobScheduled($workOrder, $scheduledFor));
        return $workOrder;
    }
}
