<?php

namespace Modules\Payroll\Contracts\Services;

use Modules\Payroll\Support\DTOs\CleaningPayrollInput;
use Modules\Payroll\Support\DTOs\CleaningPayrollResult;

interface CleaningPayrollServiceContract
{
    public function calculate(CleaningPayrollInput $input): CleaningPayrollResult;

    public function inspectRosterVariance(CleaningPayrollInput $input): array;
}
