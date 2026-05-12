<?php

namespace Modules\CleaningJobs\Actions;

use Modules\CleaningJobs\Events\JobCancelled;
use Modules\CleaningJobs\Models\WorkOrder;

class CancelJobAction
{
    public function execute(WorkOrder $workOrder, string $reason = ''): WorkOrder
    {
        $workOrder->status = 'cancelled';
        $workOrder->save();
        event(new JobCancelled($workOrder, $reason));
        return $workOrder;
    }
}
