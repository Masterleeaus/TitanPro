<?php

declare(strict_types=1);

namespace Modules\Budgeting\Actions\Expenses;

use Modules\Budgeting\Contracts\Services\ExpensesServiceContract;
use Modules\Budgeting\Models\Expense;

class RejectExpenseAction
{
    public function __construct(protected ExpensesServiceContract $service) {}

    public function handle(Expense $expense, int $approverId, ?string $notes = null): Expense
    {
        return $this->service->reject($expense, $approverId, $notes);
    }
}
