<?php

namespace Modules\Complaint\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Modules\Complaint\Entities\Complaint;
use Modules\Complaint\Support\Enums\ComplaintStatus;

class SatisfactionWidget extends BaseWidget
{
    protected static ?int $sort = 10;

    protected function getStats(): array
    {
        $resolved = Complaint::query()->where('status', ComplaintStatus::RESOLVED->value)->count();
        $open = Complaint::query()->where('status', ComplaintStatus::OPEN->value)->count();
        $pending = Complaint::query()->where('status', ComplaintStatus::PENDING->value)->count();

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
