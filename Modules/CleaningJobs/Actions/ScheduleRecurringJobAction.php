<?php

namespace Modules\CleaningJobs\Actions;

use Modules\CleaningJobs\Events\RecurringJobScheduled;
use Modules\CleaningJobs\Models\WorkOrder;

class ScheduleRecurringJobAction
{
    public function execute(WorkOrder $workOrder, string $frequency): WorkOrder
    {
        event(new RecurringJobScheduled($workOrder, $frequency));
        return $workOrder;
    }
}
