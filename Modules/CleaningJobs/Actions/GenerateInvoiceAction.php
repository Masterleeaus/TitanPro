<?php
declare(strict_types=1);
namespace Modules\CleaningJobs\Actions;

use Modules\CleaningJobs\Events\InvoiceGenerated;
use Modules\CleaningJobs\Models\WorkOrder;

class GenerateInvoiceAction
{
    public function handle(WorkOrder $job, string $invoiceRef = ''): WorkOrder
    {
        $job->update(['notes' => trim(($job->notes ?? '') . "\nInvoice: {$invoiceRef}")]);
        InvoiceGenerated::dispatch($job, 0, $invoiceRef);
        return $job;
    }
}
