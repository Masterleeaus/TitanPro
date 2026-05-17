<?php

declare(strict_types=1);

namespace Modules\Dispatch\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Modules\Dispatch\Models\AssignShift;
use Modules\Dispatch\Models\DispatchAppointment;
use Modules\Dispatch\Models\DispatchWorkOrder;

class DispatchStatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Open work orders', DispatchWorkOrder::query()->whereNotIn('status', ['completed', 'cancelled'])->count()),
            Stat::make('Today appointments', DispatchAppointment::query()->whereDate('starts_at', today())->count()),
            Stat::make('Active assignments', AssignShift::query()->whereNotIn('dispatch_status', ['completed', 'cancelled'])->count()),
        ];
    }
}
