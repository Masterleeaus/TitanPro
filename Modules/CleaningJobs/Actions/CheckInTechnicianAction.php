<?php

namespace Modules\CleaningJobs\Actions;

use Modules\CleaningJobs\Events\TechnicianCheckedIn;
use Modules\CleaningJobs\Models\WorkOrder;

class CheckInTechnicianAction
{
    public function execute(WorkOrder $workOrder, int $technicianId, ?array $coordinates = null): WorkOrder
    {
        event(new TechnicianCheckedIn($workOrder, $technicianId, $coordinates));
        return $workOrder;
    }
}
