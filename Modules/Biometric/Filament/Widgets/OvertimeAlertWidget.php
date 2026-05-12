<?php

namespace Modules\Biometric\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Modules\Biometric\Entities\BiometricAttendance;

class OvertimeAlertWidget extends BaseWidget
{
    protected static ?int $sort = 11;

    protected function getStats(): array
    {
        $highVolumeDevices = BiometricAttendance::query()
            ->select('device_serial_number')
            ->groupBy('device_serial_number')
            ->havingRaw('COUNT(*) > 20')
            ->count();

        return [
            Stat::make('Overtime Risk Alerts', $highVolumeDevices)
                ->description('Devices with high attendance bursts')
                ->icon('heroicon-o-exclamation-triangle')
                ->color($highVolumeDevices > 0 ? 'warning' : 'success'),
        ];
    }
}

