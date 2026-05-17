<?php

namespace Modules\Payroll\Console\Diagnostics;

use Illuminate\Console\Command;

class PayrollIntegrityCheckCommand extends Command
{
    protected $signature = 'payroll:integrity-check {--json : Output machine-readable status}';

    protected $description = 'Run lightweight payroll MVP integrity checks.';

    public function handle(): int
    {
        $checks = [
            'module_loaded' => true,
            'payslip_delivery_ready' => class_exists('Modules\\Payroll\\Jobs\\Notifications\\DeliverPayslipJob')
                || class_exists('Modules\\Payroll\\Jobs\\Queued\\DeliverPayslipJob'),
            'cleaner_config_present' => file_exists(module_path('Payroll', 'Config/cleaners.php')),
        ];

        if ($this->option('json')) {
            $this->line(json_encode($checks, JSON_PRETTY_PRINT));
            return self::SUCCESS;
        }

        foreach ($checks as $name => $passed) {
            $this->line(sprintf('[%s] %s', $passed ? 'ok' : 'warn', $name));
        }

        return in_array(false, $checks, true) ? self::FAILURE : self::SUCCESS;
    }
}
