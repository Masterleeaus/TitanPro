<?php

namespace Modules\CleaningJobs\Actions;

use Modules\CleaningJobs\Events\JobOnHold;
use Modules\CleaningJobs\Models\WorkOrder;

class PutJobOnHoldAction
{
    public function execute(WorkOrder $workOrder, string $reason = ''): WorkOrder
    {
        $workOrder->status = 'on_hold';
        $workOrder->save();
        event(new JobOnHold($workOrder, $reason));
        return $workOrder;
    }
}
