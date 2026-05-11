<?php

namespace App\Filament\Widgets;

use App\Support\CleaningAdminMetrics;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DispatchOverviewWidget extends StatsOverviewWidget
{
    protected ?string $heading = 'Dispatch';

    protected static ?int $sort = 2;

    protected function getStats(): array
    {
        $counts = CleaningAdminMetrics::dispatchStatusCounts();

        return [
            Stat::make('Scheduled', $counts['scheduled'])->description('Jobs waiting to be worked')->color('gray'),
            Stat::make('Assigned', $counts['assigned'])->description('Assigned to a technician')->color('info'),
            Stat::make('En route', $counts['en_route'])->description('Technicians travelling to jobs')->color('warning'),
            Stat::make('In progress', $counts['in_progress'])->description('Active onsite jobs')->color('success'),
            Stat::make('Unassigned alert', $counts['unassigned'])->description('Open jobs without technician')->color($counts['unassigned'] > 0 ? 'danger' : 'success'),
        ];
    }
}
