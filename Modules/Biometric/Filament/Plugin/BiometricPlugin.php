<?php

namespace Modules\Biometric\Filament\Plugin;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Modules\Biometric\Filament\Pages\AttendanceDashboardPage;
use Modules\Biometric\Filament\Resources\AttendanceResource;
use Modules\Biometric\Filament\Resources\BiometricDeviceResource;
use Modules\Biometric\Filament\Widgets\OvertimeAlertWidget;
use Modules\Biometric\Filament\Widgets\ShiftCoverageWidget;

class BiometricPlugin implements Plugin
{
    public function getId(): string
    {
        return 'biometric';
    }

    public function register(Panel $panel): void
    {
        $panel
            ->resources([
                AttendanceResource::class,
                BiometricDeviceResource::class,
            ])
            ->pages([
                AttendanceDashboardPage::class,
            ])
            ->widgets([
                ShiftCoverageWidget::class,
                OvertimeAlertWidget::class,
            ]);
    }

    public function boot(Panel $panel): void {}

    public static function make(): static
    {
        return app(static::class);
    }
}
