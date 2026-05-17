<?php

declare(strict_types=1);

namespace Modules\Budgeting\Listeners\Domain;

use Modules\Budgeting\Events\Domain\ExpenseSubmitted;
use Modules\Budgeting\Notifications\InApp\ExpenseApprovalNotification;

class NotifyApprovers
{
    public function handle(ExpenseSubmitted $event): void
    {
        // Notify users with approval rights for this company
        // Actual notification dispatch would resolve approvers via ApprovalThresholdsService
        $expense = $event->model;

        $approvers = \App\Models\User::query()
            ->where('company_id', $expense->company_id)
            ->whereHas('roles', fn ($q) => $q->whereIn('name', ['admin', 'finance-approver']))
            ->get();

        foreach ($approvers as $approver) {
            $approver->notify(new ExpenseApprovalNotification($expense));
        }
    }
}
