<?php

namespace Modules\CleaningJobs\Workflows\Conditions;

use Modules\CleaningJobs\Models\WorkOrder;

class HasAssignedCleaner
{
    public function check(WorkOrder $order): bool
    {
        return ! empty($order->technician_id ?? $order->assign);
    }
}
