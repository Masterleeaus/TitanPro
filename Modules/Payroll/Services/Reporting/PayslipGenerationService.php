<?php

namespace Modules\Payroll\Services\Reporting;

use Illuminate\Support\Facades\View;
use Modules\Payroll\Contracts\Services\PayslipGenerationServiceContract;
use Modules\Payroll\Support\DTOs\PayslipDocument;
use Modules\Payroll\Support\DTOs\PayrollCalculationResult;

class PayslipGenerationService implements PayslipGenerationServiceContract
{
    public function build(PayrollCalculationResult $result, array $employee = [], array $company = []): PayslipDocument
    {
        $payload = ['result' => $result->toArray(), 'employee' => $employee, 'company' => $company];
        $html = View::exists('payroll::pdf.payslip')
            ? View::make('payroll::pdf.payslip', $payload)->render()
            : $this->fallbackHtml($payload);

        return new PayslipDocument($result->userId, $result->periodFrom, $result->periodTo, $html, $payload);
    }

    private function fallbackHtml(array $payload): string
    {
        $result = $payload['result'];
        $name = e($payload['employee']['name'] ?? ('Employee #'.$result['user_id']));
        return '<h1>Payslip</h1><p>'.$name.'</p><p>Gross: '.number_format($result['gross_pay'], 2).'</p><p>Net: '.number_format($result['net_pay'], 2).'</p>';
    }
}
