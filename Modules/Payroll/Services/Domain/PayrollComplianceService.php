<?php

namespace Modules\Payroll\Services\Domain;

use Modules\Payroll\Contracts\Services\PayrollComplianceServiceContract;
use Modules\Payroll\Support\DTOs\PayrollCalculationResult;
use Modules\Payroll\Support\DTOs\PayrollComplianceReport;

class PayrollComplianceService implements PayrollComplianceServiceContract
{
    public function inspect(PayrollCalculationResult $result, array $context = []): PayrollComplianceReport
    {
        $violations = [];
        $warnings = [];
        $minimumNet = (float) config('payroll.compliance.minimum_net_pay', 0);
        $ratioLimit = (float) config('payroll.compliance.maximum_net_to_gross_ratio', 1.25);

        if ($result->netPay < $minimumNet) {
            $violations[] = ['code' => 'MIN_NET_PAY', 'message' => 'Net pay is below configured minimum.', 'amount' => $result->netPay];
        }

        if ($result->grossPay > 0 && ($result->netPay / $result->grossPay) > $ratioLimit) {
            $violations[] = ['code' => 'NET_GROSS_RATIO', 'message' => 'Net pay exceeds configured net/gross ratio.', 'ratio' => round($result->netPay / $result->grossPay, 4)];
        }

        if (($context['employee_identifier'] ?? null) === null && (bool) config('payroll.compliance.require_employee_identifier', true)) {
            $warnings[] = ['code' => 'MISSING_EMPLOYEE_IDENTIFIER', 'message' => 'Employee identifier was not supplied for compliance export.'];
        }

        foreach ($result->lines as $line) {
            if (($line['code'] ?? '') === 'OT' && (float) ($line['hours'] ?? 0) > (float) config('payroll.compliance.warn_overtime_hours_above', 20)) {
                $warnings[] = ['code' => 'HIGH_OVERTIME', 'message' => 'Overtime hours exceed warning threshold.'];
            }
        }

        return new PayrollComplianceReport(empty($violations), $violations, $warnings, ['checked_at' => now()->toISOString()]);
    }
}
