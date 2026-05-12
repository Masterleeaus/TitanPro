<?php

namespace Modules\CleaningJobs\Actions;

use Modules\CleaningJobs\Events\JobStarted;
use Modules\CleaningJobs\Models\WorkOrder;

class StartJobAction
{
    public function execute(WorkOrder $workOrder): WorkOrder
    {
        $workOrder->status = 'in_progress';
        $workOrder->save();
        event(new JobStarted($workOrder));
        return $workOrder;
    }
}
