<?php

namespace Modules\CleaningJobs\Actions;

use Modules\CleaningJobs\Events\JobAssigned;
use Modules\CleaningJobs\Models\WorkOrder;

class AssignTechnicianAction
{
    public function execute(WorkOrder $workOrder, int $technicianId): WorkOrder
    {
        $workOrder->technician_id = $technicianId;
        $workOrder->save();
        event(new JobAssigned($workOrder, $technicianId));
        return $workOrder;
    }
}
