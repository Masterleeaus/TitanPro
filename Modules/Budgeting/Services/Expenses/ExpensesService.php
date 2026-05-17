<?php

declare(strict_types=1);

namespace Modules\Budgeting\Services\Expenses;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Modules\Budgeting\Contracts\Services\ExpensesServiceContract;
use Modules\Budgeting\Events\Domain\ExpenseApproved;
use Modules\Budgeting\Events\Domain\ExpenseSubmitted;
use Modules\Budgeting\Models\Expense;

class ExpensesService implements ExpensesServiceContract
{
    public function __construct(protected Expense $model) {}

    public function submit(array $data): Expense
    {
        return DB::transaction(function () use ($data): Expense {
            $expense = $this->model->newQuery()->create(array_merge($data, [
                'status' => 'pending',
                'submitted_at' => now(),
            ]));

            event(new ExpenseSubmitted($expense));

            return $expense;
        });
    }

    public function approve(Expense $expense, int $approverId, ?string $notes = null): Expense
    {
        return DB::transaction(function () use ($expense, $approverId, $notes): Expense {
            $expense->update([
                'status' => 'approved',
                'approved_by' => $approverId,
                'approved_at' => now(),
                'notes' => $notes ?? $expense->notes,
            ]);

            event(new ExpenseApproved($expense));

            return $expense->refresh();
        });
    }

    public function reject(Expense $expense, int $approverId, ?string $notes = null): Expense
    {
        return DB::transaction(function () use ($expense, $approverId, $notes): Expense {
            $expense->update([
                'status' => 'rejected',
                'approved_by' => $approverId,
                'approved_at' => now(),
                'notes' => $notes ?? $expense->notes,
            ]);

            return $expense->refresh();
        });
    }

    public function listForCompany(int $companyId, array $filters = []): LengthAwarePaginator
    {
        $query = $this->model->newQuery()
            ->where('company_id', $companyId)
            ->with(['submittedBy', 'category', 'receipt']);

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['submitted_by'])) {
            $query->where('submitted_by', $filters['submitted_by']);
        }

        return $query->latest()->paginate(config('budgeting.pagination.per_page', 25));
    }
}
