<?php

declare(strict_types=1);

namespace Modules\Budgeting\Policies\RBAC;

use App\Models\User;
use Modules\Budgeting\Models\Expense;

class ExpensePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('budgeting.view');
    }

    public function view(User $user, Expense $expense): bool
    {
        return $user->can('budgeting.view') && $user->company_id === $expense->company_id;
    }

    public function create(User $user): bool
    {
        return $user->can('budgeting.expense.submit');
    }

    public function update(User $user, Expense $expense): bool
    {
        return $user->can('budgeting.update') && $user->company_id === $expense->company_id;
    }

    public function delete(User $user, Expense $expense): bool
    {
        return $user->can('budgeting.delete') && $user->company_id === $expense->company_id;
    }

    public function approve(User $user, Expense $expense): bool
    {
        return $user->can('budgeting.expense.approve') && $user->company_id === $expense->company_id;
    }

    public function reject(User $user, Expense $expense): bool
    {
        return $user->can('budgeting.expense.reject') && $user->company_id === $expense->company_id;
    }
}
