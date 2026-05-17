<?php

declare(strict_types=1);

namespace Modules\Budgeting\Actions\BudgetActuals;

use Modules\Budgeting\Contracts\Services\BudgetActualsServiceContract;
use Modules\Budgeting\Models\BudgetActual;

class PostActualSpendAction
{
    public function __construct(protected BudgetActualsServiceContract $service) {}

    public function handle(array $data): BudgetActual
    {
        return $this->service->postActual($data);
    }
}
