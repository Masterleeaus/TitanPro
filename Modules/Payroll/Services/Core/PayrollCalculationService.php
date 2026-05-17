<?php

namespace Modules\Payroll\Services\Core;

use Modules\Payroll\Contracts\Services\PayrollCalculationServiceContract;
use Modules\Payroll\Support\DTOs\PayrollCalculationInput;
use Modules\Payroll\Support\DTOs\PayrollCalculationResult;

class PayrollCalculationService implements PayrollCalculationServiceContract
{
    public function calculate(PayrollCalculationInput $input): PayrollCalculationResult
    {
        $regularPay = $input->hourlyRate > 0 ? $input->hourlyRate * $input->regularHours : $input->baseSalary;
        $overtimePay = $input->hourlyRate > 0 ? $input->hourlyRate * 1.5 * $input->overtimeHours : 0.0;
        $earnings = $this->sum($input->earnings) + $regularPay + $overtimePay;
        $deductions = $this->sum($input->deductions);
        $taxes = $this->sum($input->taxes);
        $reimbursements = $this->sum($input->reimbursements);
        $gross = $earnings;
        $net = max(0, $gross - $deductions - $taxes + $reimbursements);

        return new PayrollCalculationResult(
            userId: $input->userId,
            grossPay: $gross,
            netPay: $net,
            totalEarnings: $earnings,
            totalDeductions: $deductions,
            totalTaxes: $taxes,
            totalReimbursements: $reimbursements,
            lines: [
                ['code' => 'BASE', 'label' => 'Base pay', 'amount' => round($regularPay, 2)],
                ['code' => 'OT', 'label' => 'Overtime pay', 'amount' => round($overtimePay, 2)],
            ],
            warnings: $net === 0.0 && $gross > 0 ? ['Net pay resolved to zero after deductions/taxes.'] : [],
            metadata: $input->metadata,
        );
    }

    private function sum(array $items): float
    {
        return array_reduce($items, fn ($carry, $item) => $carry + (float) (is_array($item) ? ($item['amount'] ?? 0) : $item), 0.0);
    }
}
