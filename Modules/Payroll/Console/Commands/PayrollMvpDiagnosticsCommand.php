<?php

namespace Modules\Payroll\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class PayrollMvpDiagnosticsCommand extends Command
{
    protected $signature = 'payroll:mvp-diagnostics';
    protected $description = 'Run cleaner-payroll MVP readiness diagnostics.';

    public function handle(): int
    {
        foreach (['salary_slips', 'payroll_runs', 'payroll_period_locks'] as $table) {
            $this->line(($this->tableExists($table) ? 'OK   ' : 'MISS ').$table);
        }
        $this->line('Payslip delivery: '.(config('payroll.features.send_payslips_to_employees') ? 'enabled' : 'disabled'));
        $this->line('Duplicate run guard: '.(config('payroll.features.prevent_duplicate_runs', true) ? 'enabled' : 'disabled'));
        $this->line('Cleaner payroll config: '.(config('payroll.cleaning') ? 'loaded' : 'missing'));
        return self::SUCCESS;
    }

    private function tableExists(string $table): bool
    {
        try { return DB::getSchemaBuilder()->hasTable($table); } catch (\Throwable) { return false; }
    }
}
