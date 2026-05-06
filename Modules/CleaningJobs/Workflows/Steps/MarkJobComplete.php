<?php

namespace Modules\CleaningJobs\Workflows\Steps;

use Modules\CleaningJobs\Models\WorkOrder;
use Modules\CleaningJobs\Services\JobLifecycleService;

class MarkJobComplete
{
    public function __construct(private JobLifecycleService $lifecycle) {}

    public function handle(WorkOrder $order): WorkOrder
    {
        return $this->lifecycle->update($order, ['status' => 'completed']);
    }
}
