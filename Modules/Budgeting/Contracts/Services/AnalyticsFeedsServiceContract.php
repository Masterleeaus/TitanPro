<?php

declare(strict_types=1);

namespace Modules\Budgeting\Contracts\Services;

interface AnalyticsFeedsServiceContract
{
    public function getSpendTrend(int $companyId, string $period): array;

    public function getBudgetVsActual(int $companyId, string $period): array;

    public function getTopCategories(int $companyId, int $limit = 10): array;
}
