<?php

namespace Modules\Payroll\Contracts\Services;

use Carbon\CarbonInterface;
use Illuminate\Support\Collection;
use Modules\Payroll\Support\DTOs\PayrollRunResult;

interface PayrollRunServiceContract
{
    public function preview(int $companyId, CarbonInterface $from, CarbonInterface $to, ?array $userIds = null): Collection;
    public function run(int $companyId, CarbonInterface $from, CarbonInterface $to, ?array $userIds = null, array $options = []): PayrollRunResult;
}
