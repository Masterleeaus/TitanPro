<?php

namespace Modules\Payroll\Repositories\Eloquent;

use Illuminate\Support\Collection;
use Modules\Payroll\Contracts\Repositories\PayrollRunRepositoryContract;
use Modules\Payroll\Entities\PayrollRun;

class PayrollRunRepository implements PayrollRunRepositoryContract
{
    public function create(array $payload): PayrollRun
    {
        return PayrollRun::query()->create($payload);
    }

    public function update(PayrollRun $run, array $payload): PayrollRun
    {
        $run->fill($payload)->save();

        return $run->refresh();
    }

    public function findForCompany(int $companyId, int $runId): ?PayrollRun
    {
        return PayrollRun::query()->where('company_id', $companyId)->find($runId);
    }

    public function recentForCompany(int $companyId, int $limit = 20): Collection
    {
        return PayrollRun::query()->where('company_id', $companyId)->latest('id')->limit($limit)->get();
    }
}
