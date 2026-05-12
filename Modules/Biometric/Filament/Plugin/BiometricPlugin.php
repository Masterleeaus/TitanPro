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
            ->resources(array_values(array_filter([
                class_exists(AttendanceResource::class) ? AttendanceResource::class : null,
                class_exists(BiometricDeviceResource::class) ? BiometricDeviceResource::class : null,
            ])))
            ->pages(array_values(array_filter([
                class_exists(AttendanceDashboardPage::class) ? AttendanceDashboardPage::class : null,
            ])))
            ->widgets(array_values(array_filter([
                class_exists(ShiftCoverageWidget::class) ? ShiftCoverageWidget::class : null,
                class_exists(OvertimeAlertWidget::class) ? OvertimeAlertWidget::class : null,
            ])));
    }

    public function boot(Panel $panel): void {}

    public static function make(): static
    {
        return app(static::class);
    }
}
