<?php

namespace Modules\CleaningJobs\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Modules\CleaningJobs\Models\WorkOrder;

class JobBillingSummaryWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $totalRevenue = WorkOrder::where('status', 'completed')->sum('actual_revenue');
        $totalCost = WorkOrder::where('status', 'completed')->sum('actual_cost');
        $pendingEstimate = WorkOrder::whereNotIn('status', ['completed', 'cancelled'])->sum('total_estimate');
        $openCount = WorkOrder::where('status', 'open')->count();

        return [
            Stat::make('Total Revenue', '$' . number_format((float) $totalRevenue, 2))
                ->description('From completed jobs')
                ->color('success'),
            Stat::make('Total Cost', '$' . number_format((float) $totalCost, 2))
                ->description('From completed jobs')
                ->color('danger'),
            Stat::make('Pipeline Value', '$' . number_format((float) $pendingEstimate, 2))
                ->description('Open & scheduled jobs')
                ->color('info'),
            Stat::make('Open Jobs', (string) $openCount)
                ->description('Awaiting assignment')
                ->color('warning'),
        ];
    }
}
