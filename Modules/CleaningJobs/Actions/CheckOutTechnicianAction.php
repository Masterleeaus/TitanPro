<?php

namespace Modules\CleaningJobs\Actions;

use Modules\CleaningJobs\Events\TechnicianCheckedOut;
use Modules\CleaningJobs\Models\WorkOrder;

class CheckOutTechnicianAction
{
    public function execute(WorkOrder $workOrder, int $technicianId): WorkOrder
    {
        event(new TechnicianCheckedOut($workOrder, $technicianId));
        return $workOrder;
    }
}
