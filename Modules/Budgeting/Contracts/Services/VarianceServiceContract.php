<?php

declare(strict_types=1);

namespace Modules\Budgeting\Contracts\Services;

use Modules\Budgeting\Models\BudgetActual;
use Modules\Budgeting\Models\BudgetVariance;

interface VarianceServiceContract
{
    public function calculate(BudgetActual $actual): BudgetVariance;

    public function flagAnomalies(int $companyId): int;

    public function getReport(int $companyId, array $filters = []): array;
}
