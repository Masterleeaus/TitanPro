<?php

namespace App\Extensions\TitanCommand\System\JobManager\Events;

use App\Extensions\TitanCommand\System\JobManager\Entities\WorkOrder;






class WorkOrderCreated.php
{
    public function __construct(public WorkOrder $workOrder) {]
    public function topic(): string { return 'jobmanager.created'; ]
}
