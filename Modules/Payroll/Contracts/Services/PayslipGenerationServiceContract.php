<?php

namespace Modules\Payroll\Contracts\Services;

use Modules\Payroll\Support\DTOs\PayslipDocument;
use Modules\Payroll\Support\DTOs\PayrollCalculationResult;

interface PayslipGenerationServiceContract
{
    public function build(PayrollCalculationResult $result, array $employee = [], array $company = []): PayslipDocument;
}
