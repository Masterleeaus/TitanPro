<?php

namespace Modules\CleaningJobs\Actions;

use Modules\CleaningJobs\Events\InvoiceGenerated;
use Modules\CleaningJobs\Models\WorkOrder;

class GenerateInvoiceAction
{
    public function execute(WorkOrder $workOrder, string $invoiceRef, float $amount): WorkOrder
    {
        event(new InvoiceGenerated($workOrder, $invoiceRef, $amount));
        return $workOrder;
    }
}
