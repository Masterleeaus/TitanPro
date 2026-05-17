<?php

namespace Modules\Payroll\Monitoring\Health;

use Illuminate\Support\Facades\Schema;

class PayrollHealthCheck
{
    public function check(): array
    {
        $requiredTables = ['salary_slips', 'employee_monthly_salaries', 'payroll_runs', 'payroll_run_approvals'];
        $missing = array_values(array_filter($requiredTables, fn (string $table) => ! Schema::hasTable($table)));

        return [
            'status' => empty($missing) ? 'healthy' : 'degraded',
            'missing_tables' => $missing,
            'features' => config('payroll.features', []),
        ];
    }
}
