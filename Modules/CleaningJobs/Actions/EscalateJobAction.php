<?php

namespace Modules\CleaningJobs\Actions;

use Modules\CleaningJobs\Events\JobEscalated;
use Modules\CleaningJobs\Models\WorkOrder;

class EscalateJobAction
{
    public function execute(WorkOrder $workOrder, int $escalatedTo, string $reason): WorkOrder
    {
        event(new JobEscalated($workOrder, $escalatedTo, $reason));
        return $workOrder;
    }
}
