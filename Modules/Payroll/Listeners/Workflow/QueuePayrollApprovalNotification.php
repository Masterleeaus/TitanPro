<?php

namespace Modules\Payroll\Listeners\Workflow;

use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Payroll\Events\Workflow\PayrollRunSubmitted;
use Modules\Payroll\Notifications\Workflow\PayrollRunApprovalRequested;

class QueuePayrollApprovalNotification implements ShouldQueue
{
    public function handle(PayrollRunSubmitted $event): void
    {
        User::query()
            ->where('company_id', $event->run->company_id)
            ->whereHas('roles', fn ($query) => $query->whereIn('name', ['admin', 'finance', 'payroll']))
            ->get()
            ->each(fn (User $user) => $user->notify(new PayrollRunApprovalRequested($event->run)));
    }
}
