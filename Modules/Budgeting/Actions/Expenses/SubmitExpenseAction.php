<?php

declare(strict_types=1);

namespace Modules\Budgeting\Actions\Expenses;

use Modules\Budgeting\Contracts\Services\ExpensesServiceContract;
use Modules\Budgeting\Models\Expense;

class SubmitExpenseAction
{
    public function __construct(protected ExpensesServiceContract $service) {}

    public function handle(array $data): Expense
    {
        return $this->service->submit($data);
    }
}
