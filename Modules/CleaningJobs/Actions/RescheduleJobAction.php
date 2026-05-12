<?php

namespace Modules\CleaningJobs\Actions;

use DateTimeInterface;
use Modules\CleaningJobs\Events\JobRescheduled;
use Modules\CleaningJobs\Models\WorkOrder;

class RescheduleJobAction
{
    public function execute(WorkOrder $workOrder, DateTimeInterface $newDate): WorkOrder
    {
        $workOrder->scheduled_for = $newDate;
        $workOrder->save();
        event(new JobRescheduled($workOrder, $newDate));
        return $workOrder;
    }
}
