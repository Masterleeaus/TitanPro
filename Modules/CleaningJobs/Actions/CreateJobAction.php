<?php

namespace Modules\CleaningJobs\Actions;

use Modules\CleaningJobs\Events\JobCreated;
use Modules\CleaningJobs\Models\WorkOrder;

class CreateJobAction
{
    public function execute(array $data): WorkOrder
    {
        $order = WorkOrder::create($data);
        event(new JobCreated($order));
        return $order;
    }
}
