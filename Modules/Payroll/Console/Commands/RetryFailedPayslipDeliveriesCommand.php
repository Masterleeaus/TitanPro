<?php

namespace Modules\Payroll\Console\Commands;

use Illuminate\Console\Command;
use Modules\Payroll\Jobs\Scheduled\RetryFailedPayslipDeliveriesJob;

class RetryFailedPayslipDeliveriesCommand extends Command
{
    protected $signature = 'payroll:payslips:retry-failed';
    protected $description = 'Queue failed payslip deliveries for retry.';

    public function handle(): int
    {
        RetryFailedPayslipDeliveriesJob::dispatch();
        $this->info('Failed payslip delivery retry job queued.');
        return self::SUCCESS;
    }
}
