<?php

namespace Modules\Biometric\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Modules\Biometric\Entities\BiometricAttendance;

class ShiftCoverageWidget extends BaseWidget
{
    protected static ?int $sort = 10;

    protected function getStats(): array
    {
        $todayAttendances = BiometricAttendance::query()->whereDate('timestamp', now()->toDateString())->count();

        return [
            Stat::make('Today Check-ins', $todayAttendances)
                ->description('Recorded from biometric module')
                ->icon('heroicon-o-user-group')
                ->color('success'),
        ];
    }
}

