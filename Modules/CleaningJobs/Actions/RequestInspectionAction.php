<?php

namespace Modules\CleaningJobs\Actions;

use Modules\CleaningJobs\Events\InspectionRequested;
use Modules\CleaningJobs\Models\WorkOrder;

class RequestInspectionAction
{
    public function execute(WorkOrder $workOrder): WorkOrder
    {
        event(new InspectionRequested($workOrder));
        return $workOrder;
    }
}
