<?php

namespace Modules\CleaningJobs\Actions;

use DateTimeInterface;
use Modules\CleaningJobs\Events\SlaBreached;
use Modules\CleaningJobs\Models\WorkOrder;

class TriggerSlaBreachAction
{
    public function execute(WorkOrder $workOrder, ?DateTimeInterface $slaDue = null): WorkOrder
    {
        event(new SlaBreached($workOrder, $slaDue ?? now()));
        return $workOrder;
    }
}
