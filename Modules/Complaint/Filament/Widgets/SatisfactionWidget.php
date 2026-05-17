<?php

namespace Modules\Complaint\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Modules\Complaint\Entities\Complaint;

class SatisfactionWidget extends BaseWidget
{
    protected static ?int $sort = 10;

    protected function getStats(): array
    {
        $resolved = Complaint::query()->where('status', 'resolved')->count();
        $open = Complaint::query()->where('status', 'open')->count();
        $pending = Complaint::query()->where('status', 'pending')->count();

        return [
            Stat::make('Resolved complaints', $resolved)
                ->description('Resolved complaints to date')
                ->color('success'),
            Stat::make('Open complaints', $open)
                ->description('Complaints still open')
                ->color($open > 0 ? 'warning' : 'success'),
            Stat::make('Pending escalations', $pending)
                ->description('Complaints awaiting escalation resolution')
                ->color($pending > 0 ? 'danger' : 'success'),
        ];
    }
}
