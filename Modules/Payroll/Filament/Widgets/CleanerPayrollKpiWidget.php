<?php

namespace Modules\Payroll\Filament\Widgets;

use Illuminate\Support\Facades\DB;

class CleanerPayrollKpiWidget
{
    public function cards(): array
    {
        return [
            'open_payroll_runs' => $this->count('payroll_runs', ['status' => 'draft']),
            'pending_approvals' => $this->count('payroll_run_approvals', ['status' => 'pending']),
            'failed_payslip_deliveries' => $this->count('payroll_payslip_deliveries', ['status' => 'failed']),
            'locked_periods' => $this->count('payroll_period_locks', ['locked' => 1]),
        ];
    }

    private function count(string $table, array $where = []): int
    {
        if (! app('db')->getSchemaBuilder()->hasTable($table)) {
            return 0;
        }

        $query = DB::table($table);
        foreach ($where as $column => $value) {
            $query->where($column, $value);
        }

        return (int) $query->count();
    }
}
