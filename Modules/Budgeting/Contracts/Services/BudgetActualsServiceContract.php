<?php

declare(strict_types=1);

namespace Modules\Budgeting\Contracts\Services;

use Modules\Budgeting\Models\BudgetActual;

interface BudgetActualsServiceContract
{
    public function postActual(array $data): BudgetActual;

    public function reconcile(BudgetActual $actual): BudgetActual;

    public function lock(BudgetActual $actual): BudgetActual;
}
