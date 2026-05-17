<?php

declare(strict_types=1);

namespace Modules\Budgeting\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Modules\Budgeting\Models\BudgetVariance;

class VarianceAlertWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $companyId = auth()->user()?->company_id;

        $counts = BudgetVariance::query()
            ->where('company_id', $companyId)
            ->selectRaw("
                SUM(CASE WHEN flag = 'critical' THEN 1 ELSE 0 END) as critical,
                SUM(CASE WHEN flag = 'warning' THEN 1 ELSE 0 END) as warning,
                SUM(CASE WHEN anomaly_flagged = 1 THEN 1 ELSE 0 END) as anomalies
            ")
            ->first();

        return [
            Stat::make('Critical Variances', (int) ($counts->critical ?? 0))
                ->color('danger'),
            Stat::make('Warning Variances', (int) ($counts->warning ?? 0))
                ->color('warning'),
            Stat::make('Anomalies Flagged', (int) ($counts->anomalies ?? 0))
                ->color('primary'),
        ];
    }
}
