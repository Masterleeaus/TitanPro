<?php

namespace Modules\CleaningJobs\Actions;

use Modules\CleaningJobs\Events\ConsumablesUsed;
use Modules\CleaningJobs\Models\WorkOrder;

class LogConsumableUsageAction
{
    public function execute(WorkOrder $workOrder, array $items): WorkOrder
    {
        event(new ConsumablesUsed($workOrder, $items));
        return $workOrder;
    }
}
