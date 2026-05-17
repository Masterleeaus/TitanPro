<?php

namespace Modules\Payroll\Services\Reporting;

use Illuminate\Support\Facades\DB;
use Modules\Payroll\Contracts\Services\PayrollSummaryServiceContract;

class PayrollSummaryService implements PayrollSummaryServiceContract
{
    public function summarize(array $filters = []): array
    {
        $companyId = $filters['company_id'] ?? (function_exists('company') ? company()->id : null);
        $from = $filters['period_from'] ?? now()->startOfMonth()->toDateString();
        $to = $filters['period_to'] ?? now()->endOfMonth()->toDateString();
        $summary = ['gross' => 0.0, 'net' => 0.0, 'tax' => 0.0, 'employees' => 0, 'runs' => 0];

        if (DB::getSchemaBuilder()->hasTable('payroll_runs')) {
            $runs = DB::table('payroll_runs')
                ->when($companyId, fn ($q) => $q->where('company_id', $companyId))
                ->whereBetween('period_from', [$from, $to]);
            $summary['runs'] = (clone $runs)->count();
            $summary['gross'] = (float) (clone $runs)->sum('gross_pay');
            $summary['net'] = (float) (clone $runs)->sum('net_pay');
            $summary['tax'] = (float) (clone $runs)->sum('tax_total');
        }

        if (DB::getSchemaBuilder()->hasTable('salary_slips')) {
            $summary['employees'] = DB::table('salary_slips')
                ->when($companyId, fn ($q) => $q->where('company_id', $companyId))
                ->whereBetween('created_at', [$from.' 00:00:00', $to.' 23:59:59'])
                ->distinct('user_id')
                ->count('user_id');
        }

        return ['period_from' => $from, 'period_to' => $to, 'company_id' => $companyId] + $summary;
    }
}
