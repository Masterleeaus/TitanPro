<?php

namespace Modules\Payroll\Services\Analytics;

class CleanerPayrollSummaryService
{
    public function summarize(array $runs): array
    {
        $gross = 0.0;
        $net = 0.0;
        $employees = [];

        foreach ($runs as $run) {
            $gross += (float) ($run['gross_pay'] ?? 0);
            $net += (float) ($run['net_pay'] ?? 0);
            if (! empty($run['employee_id'])) {
                $employees[$run['employee_id']] = true;
            }
        }

        return [
            'runs' => count($runs),
            'employees' => count($employees),
            'gross_pay' => round($gross, 2),
            'net_pay' => round($net, 2),
        ];
    }
}
