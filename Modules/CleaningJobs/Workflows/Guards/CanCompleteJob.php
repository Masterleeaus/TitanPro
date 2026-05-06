<?php

namespace Modules\CleaningJobs\Workflows\Guards;

use Modules\CleaningJobs\Models\WorkOrder;

class CanCompleteJob
{
    public function __invoke(WorkOrder $order): bool
    {
        return (bool) ($order->technician_id ?? $order->assign ?? false);
    }
}
