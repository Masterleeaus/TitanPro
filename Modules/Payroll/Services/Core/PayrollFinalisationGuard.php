<?php

namespace Modules\Payroll\Services\Core;

class PayrollFinalisationGuard
{
    public function validate(array $payrollRun): array
    {
        $issues = [];

        if (empty($payrollRun['period_id'] ?? null)) {
            $issues[] = 'Payroll run is missing a period identifier.';
        }

        if (($payrollRun['status'] ?? null) === 'finalised') {
            $issues[] = 'Payroll run has already been finalised.';
        }

        if (! empty($payrollRun['requires_reconciliation']) && empty($payrollRun['reconciled_at'])) {
            $issues[] = 'Cleaner shift/site reconciliation must be completed before finalisation.';
        }

        return [
            'allowed' => count($issues) === 0,
            'issues' => $issues,
        ];
    }
}
