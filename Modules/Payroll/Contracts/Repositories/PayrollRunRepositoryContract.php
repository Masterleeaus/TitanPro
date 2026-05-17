<?php

namespace Modules\Payroll\Contracts\Repositories;

use Illuminate\Support\Collection;
use Modules\Payroll\Entities\PayrollRun;

interface PayrollRunRepositoryContract
{
    public function create(array $payload): PayrollRun;

    public function update(PayrollRun $run, array $payload): PayrollRun;

    public function findForCompany(int $companyId, int $runId): ?PayrollRun;

    public function recentForCompany(int $companyId, int $limit = 20): Collection;
}
