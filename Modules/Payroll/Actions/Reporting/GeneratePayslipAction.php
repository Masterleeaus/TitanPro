<?php

namespace Modules\Payroll\Actions\Reporting;

use Carbon\Carbon;
use Modules\Payroll\Contracts\Services\PayslipDeliveryServiceContract;
use Modules\Payroll\Contracts\Services\PayslipGenerationServiceContract;
use Modules\Payroll\Support\DTOs\PayrollCalculationResult;

class GeneratePayslipAction
{
    public function __construct(
        private readonly PayslipGenerationServiceContract $payslips,
        private readonly PayslipDeliveryServiceContract $delivery,
    ) {}

    public function execute(array $result, array $employee = [], array $company = [], array $options = []): array
    {
        $dto = new PayrollCalculationResult(
            userId: (int) $result['user_id'],
            grossPay: (float) $result['gross_pay'],
            netPay: (float) $result['net_pay'],
            totalEarnings: (float) ($result['total_earnings'] ?? $result['gross_pay']),
            totalDeductions: (float) ($result['total_deductions'] ?? 0),
            totalTaxes: (float) ($result['total_taxes'] ?? 0),
            totalReimbursements: (float) ($result['total_reimbursements'] ?? 0),
            lines: $result['lines'] ?? [],
            warnings: $result['warnings'] ?? [],
            metadata: $result['metadata'] ?? [],
            periodFrom: (string) ($result['period_from'] ?? Carbon::now()->startOfMonth()->toDateString()),
            periodTo: (string) ($result['period_to'] ?? Carbon::now()->endOfMonth()->toDateString())
        );

        $document = $this->payslips->build($dto, $employee, $company);
        $payload = $document->toArray();

        if ((bool) ($options['send'] ?? config('payroll.features.send_payslips_to_employees', true))) {
            $payload['delivery'] = $this->delivery->deliver($document, $employee, array_merge($options, [
                'company' => $company,
            ]))->toArray();
        }

        return $payload;
    }
}
