<?php

declare(strict_types=1);

namespace Modules\Budgeting\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Modules\Budgeting\Models\BudgetActual;

class BudgetVsActualWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $companyId = auth()->user()?->company_id;

        $totals = BudgetActual::query()
            ->where('company_id', $companyId)
            ->selectRaw('SUM(planned_amount) as planned, SUM(actual_amount) as actual')
            ->first();

        $planned = (float) ($totals->planned ?? 0);
        $actual = (float) ($totals->actual ?? 0);
        $variance = $actual - $planned;

        return [
            Stat::make('Total Planned', number_format($planned, 2))
                ->description('Budget planned')
                ->color('primary'),
            Stat::make('Total Actual', number_format($actual, 2))
                ->description('Actual spend')
                ->color($actual > $planned ? 'danger' : 'success'),
            Stat::make('Variance', number_format($variance, 2))
                ->description($variance > 0 ? 'Over budget' : 'Under budget')
                ->color($variance > 0 ? 'danger' : 'success'),
        ];
    }
}
