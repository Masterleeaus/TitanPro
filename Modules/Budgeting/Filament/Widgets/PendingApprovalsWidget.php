<?php

declare(strict_types=1);

namespace Modules\Budgeting\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Modules\Budgeting\Models\Expense;

class PendingApprovalsWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $companyId = auth()->user()?->company_id;

        $pendingCount = Expense::query()
            ->where('company_id', $companyId)
            ->where('status', 'pending')
            ->count();

        return [
            Stat::make('Pending Approvals', $pendingCount)
                ->description('Expenses awaiting approval')
                ->color($pendingCount > 0 ? 'warning' : 'success')
                ->icon('heroicon-o-clock'),
        ];
    }
}
