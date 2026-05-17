<?php

declare(strict_types=1);

namespace Modules\Budgeting\Contracts\Services;

use Modules\Budgeting\Models\Expense;

interface ExpensesServiceContract
{
    public function submit(array $data): Expense;

    public function approve(Expense $expense, int $approverId, ?string $notes = null): Expense;

    public function reject(Expense $expense, int $approverId, ?string $notes = null): Expense;

    public function listForCompany(int $companyId, array $filters = []): \Illuminate\Contracts\Pagination\LengthAwarePaginator;
}
