<?php

namespace Modules\CleaningJobs\Actions;

use Modules\CleaningJobs\Events\InspectionCompleted;
use Modules\CleaningJobs\Models\WorkOrder;

class CompleteInspectionAction
{
    public function execute(WorkOrder $workOrder, bool $passed, ?string $notes = null): WorkOrder
    {
        event(new InspectionCompleted($workOrder, $passed, $notes));
        return $workOrder;
    }
}
