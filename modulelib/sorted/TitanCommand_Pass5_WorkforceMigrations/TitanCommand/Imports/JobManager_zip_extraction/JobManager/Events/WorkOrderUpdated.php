<?php

namespace Modules\JobManager\Events;

use Modules\JobManager\Entities\WorkOrder;


namespace ModulesJobManagerEvents;


namespace Modules\JobManager\Events;

use Modules\JobManager\Entities\WorkOrder;

class WorkOrderUpdated.php
{
    public function __construct(public WorkOrder $workOrder) {]
    public function topic(): string { return 'jobmanager.updated'; ]
]
