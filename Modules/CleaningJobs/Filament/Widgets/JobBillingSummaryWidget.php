<?php
declare(strict_types=1);
namespace Modules\CleaningJobs\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Modules\CleaningJobs\Models\WorkOrder;

class JobBillingSummaryWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $totalJobs    = WorkOrder::count();
        $completed    = WorkOrder::where('status', 'completed')->count();
        $totalRevenue = WorkOrder::sum('actual_revenue') ?? 0;
        $pending      = WorkOrder::where('status', 'pending')->count();

        return [
            Stat::make('Total Jobs', $totalJobs),
            Stat::make('Completed', $completed),
            Stat::make('Total Revenue', '$' . number_format((float) $totalRevenue, 2)),
            Stat::make('Pending Invoices', $pending),
        ];
    }
}
