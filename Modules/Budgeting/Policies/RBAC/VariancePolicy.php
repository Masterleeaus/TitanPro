<?php

declare(strict_types=1);

namespace Modules\Budgeting\Policies\RBAC;

use App\Models\User;
use Modules\Budgeting\Models\BudgetVariance;

class VariancePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('budgeting.variance.view');
    }

    public function view(User $user, BudgetVariance $variance): bool
    {
        return $user->can('budgeting.variance.view') && $user->company_id === $variance->company_id;
    }
}
