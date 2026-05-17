<?php

declare(strict_types=1);

namespace Modules\Budgeting\Actions\Variance;

use Modules\Budgeting\Contracts\Services\VarianceServiceContract;
use Modules\Budgeting\Models\BudgetActual;
use Modules\Budgeting\Models\BudgetVariance;

class ExplainVarianceAction
{
    public function __construct(protected VarianceServiceContract $service) {}

    public function handle(BudgetActual $actual): BudgetVariance
    {
        return $this->service->calculate($actual);
    }
}
