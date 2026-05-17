<?php

namespace Modules\Payroll\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Modules\Payroll\Entities\PayrollRun;

class PayrollRunOverviewWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Payroll Runs', PayrollRun::query()->count()),
            Stat::make('Pending Approval', PayrollRun::query()->where('status', 'pending_approval')->count()),
            Stat::make('Approved', PayrollRun::query()->where('status', 'approved')->count()),
        ];
    }
}
