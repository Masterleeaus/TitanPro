<?php

namespace Modules\Payroll\Contracts\Actions;

use Carbon\CarbonInterface;
use Modules\Payroll\Support\DTOs\PayrollRunResult;

interface GeneratePayrollContract
{
    public function execute(int $companyId, CarbonInterface $from, CarbonInterface $to, ?array $userIds = null, array $options = []): PayrollRunResult;
}
