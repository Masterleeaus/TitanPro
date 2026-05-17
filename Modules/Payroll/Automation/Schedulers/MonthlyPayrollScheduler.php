<?php

namespace Modules\Payroll\Automation\Schedulers;

use Illuminate\Console\Scheduling\Schedule;
use Modules\Payroll\Jobs\Queued\GeneratePayrollRunJob;

class MonthlyPayrollScheduler
{
    public function register(Schedule $schedule, int $companyId): void
    {
        $schedule->job(new GeneratePayrollRunJob($companyId, now()->startOfMonth()->toDateString(), now()->endOfMonth()->toDateString()))->monthlyOn(25, '02:00')->withoutOverlapping();
    }
}
