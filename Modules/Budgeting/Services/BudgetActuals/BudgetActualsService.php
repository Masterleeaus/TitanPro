<?php

declare(strict_types=1);

namespace Modules\Budgeting\Services\BudgetActuals;

use Illuminate\Support\Facades\DB;
use Modules\Budgeting\Contracts\Services\BudgetActualsServiceContract;
use Modules\Budgeting\Events\Domain\BudgetActualPosted;
use Modules\Budgeting\Models\BudgetActual;

class BudgetActualsService implements BudgetActualsServiceContract
{
    public function __construct(protected BudgetActual $model) {}

    public function postActual(array $data): BudgetActual
    {
        return DB::transaction(function () use ($data): BudgetActual {
            $actual = $this->model->newQuery()->create($data);

            event(new BudgetActualPosted($actual));

            return $actual;
        });
    }

    public function reconcile(BudgetActual $actual): BudgetActual
    {
        // Reconcile actual spend from expense records in the same period/category
        $actualSpend = \Modules\Budgeting\Models\Expense::query()
            ->where('company_id', $actual->company_id)
            ->where('status', 'approved')
            ->when($actual->category_id, fn ($q) => $q->where('category_id', $actual->category_id))
            ->whereBetween('submitted_at', [$actual->period_start, $actual->period_end])
            ->sum('amount');

        $actual->update(['actual_amount' => $actualSpend]);

        return $actual->refresh();
    }

    public function lock(BudgetActual $actual): BudgetActual
    {
        $actual->update(['locked_at' => now()]);

        return $actual->refresh();
    }
}
