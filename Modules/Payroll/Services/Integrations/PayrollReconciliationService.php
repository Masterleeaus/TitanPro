<?php

namespace Modules\Payroll\Services\Integrations;

use Modules\Payroll\Contracts\Services\PayrollReconciliationServiceContract;

class PayrollReconciliationService implements PayrollReconciliationServiceContract
{
    public function reconcile(array $expected, array $actual): array
    {
        $actualByKey = [];
        foreach ($actual as $row) {
            $actualByKey[(string) ($row['employee_id'] ?? $row['user_id'] ?? '')] = $row;
        }
        $matched = $missing = $variance = [];
        foreach ($expected as $row) {
            $key = (string) ($row['employee_id'] ?? $row['user_id'] ?? '');
            if (! isset($actualByKey[$key])) {
                $missing[] = $row;
                continue;
            }
            $actualAmount = (float) ($actualByKey[$key]['amount'] ?? $actualByKey[$key]['net_pay'] ?? 0);
            $expectedAmount = (float) ($row['amount'] ?? $row['net_pay'] ?? 0);
            $delta = round($actualAmount - $expectedAmount, 2);
            if ($delta === 0.0) {
                $matched[] = $row + ['matched_amount' => $actualAmount];
            } else {
                $variance[] = ['key' => $key, 'expected' => $row, 'actual' => $actualByKey[$key], 'delta' => $delta];
            }
        }
        return ['matched' => $matched, 'missing' => $missing, 'variance' => $variance, 'summary' => ['matched' => count($matched), 'missing' => count($missing), 'variance' => count($variance)]];
    }
}
