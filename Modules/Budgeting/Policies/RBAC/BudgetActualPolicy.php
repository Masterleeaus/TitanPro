<?php

declare(strict_types=1);

namespace Modules\Budgeting\Policies\RBAC;

use App\Models\User;
use Modules\Budgeting\Models\BudgetActual;

class BudgetActualPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('budgeting.actuals.view');
    }

    public function view(User $user, BudgetActual $actual): bool
    {
        return $user->can('budgeting.actuals.view') && $user->company_id === $actual->company_id;
    }

    public function create(User $user): bool
    {
        return $user->can('budgeting.actuals.post');
    }

    public function update(User $user, BudgetActual $actual): bool
    {
        return $user->can('budgeting.actuals.post') && $user->company_id === $actual->company_id && $actual->locked_at === null;
    }

    public function delete(User $user, BudgetActual $actual): bool
    {
        return $user->can('budgeting.delete') && $user->company_id === $actual->company_id && $actual->locked_at === null;
    }
}
