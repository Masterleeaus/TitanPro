<?php

namespace Modules\CleaningJobs\Actions;

use Modules\CleaningJobs\Events\PaymentReceived;
use Modules\CleaningJobs\Models\WorkOrder;

class RecordPaymentAction
{
    public function execute(WorkOrder $workOrder, float $amount, string $method): WorkOrder
    {
        event(new PaymentReceived($workOrder, $amount, $method));
        return $workOrder;
    }
}
