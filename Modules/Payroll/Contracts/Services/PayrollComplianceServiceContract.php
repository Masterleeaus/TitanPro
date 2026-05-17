<?php

namespace Modules\Payroll\Contracts\Services;

use Modules\Payroll\Support\DTOs\PayrollCalculationResult;
use Modules\Payroll\Support\DTOs\PayrollComplianceReport;

interface PayrollComplianceServiceContract
{
    public function inspect(PayrollCalculationResult $result, array $context = []): PayrollComplianceReport;
}
