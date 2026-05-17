<?php

namespace Modules\Payroll\Actions\Automation;

use Carbon\CarbonInterface;
use Modules\Payroll\Contracts\Actions\GeneratePayrollContract;
use Modules\Payroll\Contracts\Services\PayrollRunServiceContract;
use Modules\Payroll\Support\DTOs\PayrollRunResult;

class RunPayrollAction implements GeneratePayrollContract
{
    public function __construct(private readonly PayrollRunServiceContract $service) {}
    public function execute(int $companyId, CarbonInterface $from, CarbonInterface $to, ?array $userIds = null, array $options = []): PayrollRunResult
    {
        return $this->service->run($companyId, $from, $to, $userIds, $options);
    }
}
