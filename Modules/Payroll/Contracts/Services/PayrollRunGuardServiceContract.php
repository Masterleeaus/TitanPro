<?php

namespace Modules\Payroll\Contracts\Services;

use Modules\Payroll\Support\DTOs\PayrollRunGuardResult;

interface PayrollRunGuardServiceContract
{
    public function inspect(array $payload): PayrollRunGuardResult;
    public function assertCanFinalize(int|string $runId, array $context = []): PayrollRunGuardResult;
}
