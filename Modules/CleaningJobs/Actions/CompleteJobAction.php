<?php

namespace Modules\CleaningJobs\Actions;

use Modules\CleaningJobs\Events\JobCompleted;
use Modules\CleaningJobs\Models\WorkOrder;

class CompleteJobAction
{
    public function execute(WorkOrder $workOrder): WorkOrder
    {
        $workOrder->status = 'completed';
        $workOrder->save();
        event(new JobCompleted($workOrder));
        return $workOrder;
    }
}
