<?php

namespace Modules\CleaningJobs\Events;

use Modules\CleaningJobs\Models\WorkOrder;

class WorkOrderCreated
{
    public function __construct(public WorkOrder $workOrder) {}
    public function topic(): string { return 'cleaningjobs.created'; }
}
