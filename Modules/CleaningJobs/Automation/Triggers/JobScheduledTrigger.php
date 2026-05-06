<?php

namespace Modules\CleaningJobs\Automation\Triggers;

use Modules\CleaningJobs\Models\WorkOrder;

class JobScheduledTrigger
{
    public function shouldRun(WorkOrder $order): bool
    {
        return ! empty($order->scheduled_for) && in_array($order->status, ['open', 'scheduled'], true);
    }
}
