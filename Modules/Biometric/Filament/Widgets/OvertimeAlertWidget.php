<?php

namespace Modules\Biometric\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Modules\Biometric\Entities\BiometricAttendance;

class OvertimeAlertWidget extends BaseWidget
{
    protected static ?int $sort = 11;
    private const HIGH_VOLUME_THRESHOLD = 20;

    protected function getStats(): array
    {
        $overtimeRiskAlertCount = BiometricAttendance::query()
            ->select('device_serial_number')
            ->groupBy('device_serial_number')
            ->havingRaw('COUNT(*) > ?', [self::HIGH_VOLUME_THRESHOLD])
            ->count();

        return [
            Stat::make('Overtime Risk Alerts', $overtimeRiskAlertCount)
                ->description('Devices with high attendance bursts')
                ->icon('heroicon-o-exclamation-triangle')
                ->color($overtimeRiskAlertCount > 0 ? 'warning' : 'success'),
        ];
    }
}
