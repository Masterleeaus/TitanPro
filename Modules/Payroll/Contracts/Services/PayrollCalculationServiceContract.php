<?php

namespace Modules\Payroll\Contracts\Services;

use Modules\Payroll\Support\DTOs\PayrollCalculationInput;
use Modules\Payroll\Support\DTOs\PayrollCalculationResult;

interface PayrollCalculationServiceContract
{
    public function calculate(PayrollCalculationInput $input): PayrollCalculationResult;
}
