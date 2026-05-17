<?php

namespace Modules\Payroll\Contracts\Repositories;

use Carbon\CarbonInterface;
use Illuminate\Support\Collection;
use Modules\Payroll\Entities\SalarySlip;

interface SalarySlipRepositoryContract
{
    public function findForPeriod(int $companyId, CarbonInterface $from, CarbonInterface $to, ?array $userIds = null): Collection;
    public function createOrUpdateForEmployee(array $attributes, array $values): SalarySlip;
}
