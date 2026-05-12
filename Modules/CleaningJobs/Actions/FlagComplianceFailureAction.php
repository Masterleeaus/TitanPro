<?php

namespace Modules\CleaningJobs\Actions;

use Modules\CleaningJobs\Events\ComplianceCheckFailed;
use Modules\CleaningJobs\Models\WorkOrder;

class FlagComplianceFailureAction
{
    public function execute(WorkOrder $workOrder, string $checkName, string $reason): WorkOrder
    {
        event(new ComplianceCheckFailed($workOrder, $checkName, $reason));
        return $workOrder;
    }
}
