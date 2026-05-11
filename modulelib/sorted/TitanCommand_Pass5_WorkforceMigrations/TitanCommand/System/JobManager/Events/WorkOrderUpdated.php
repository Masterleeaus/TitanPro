<?php

namespace App\Extensions\TitanCommand\System\JobManager\Events;

use App\Extensions\TitanCommand\System\JobManager\Entities\WorkOrder;






class WorkOrderUpdated.php
{
    public function __construct(public WorkOrder $workOrder) {]
    public function topic(): string { return 'jobmanager.updated'; ]
}
