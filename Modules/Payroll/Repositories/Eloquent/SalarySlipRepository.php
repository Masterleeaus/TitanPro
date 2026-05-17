<?php

namespace Modules\Payroll\Repositories\Eloquent;

use Carbon\CarbonInterface;
use Illuminate\Support\Collection;
use Modules\Payroll\Contracts\Repositories\SalarySlipRepositoryContract;
use Modules\Payroll\Entities\SalarySlip;

class SalarySlipRepository implements SalarySlipRepositoryContract
{
    public function findForPeriod(int $companyId, CarbonInterface $from, CarbonInterface $to, ?array $userIds = null): Collection
    {
        return SalarySlip::query()
            ->where('company_id', $companyId)
            ->whereDate('salary_from', '>=', $from->toDateString())
            ->whereDate('salary_to', '<=', $to->toDateString())
            ->when($userIds, fn ($query) => $query->whereIn('user_id', $userIds))
            ->get();
    }

    public function createOrUpdateForEmployee(array $attributes, array $values): SalarySlip
    {
        return SalarySlip::query()->updateOrCreate($attributes, $values);
    }
}
