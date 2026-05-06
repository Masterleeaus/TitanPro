<?php

namespace Modules\CleaningJobs\Workflows\Transitions;

use Modules\CleaningJobs\Models\WorkOrder;
use Modules\CleaningJobs\Services\JobLifecycleService;

class OpenToScheduled
{
    public function __construct(private JobLifecycleService $lifecycle) {}

    public function handle(WorkOrder $order, array $schedule): WorkOrder
    {
        return $this->lifecycle->update($order, array_merge($schedule, ['status' => 'scheduled']));
    }
}
