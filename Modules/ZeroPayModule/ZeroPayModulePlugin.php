<?php

namespace Modules\ZeroPayModule;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Modules\ZeroPayModule\Filament\Pages\ZeroPayControlPanel;
use Modules\ZeroPayModule\Filament\Pages\ZeroPayDashboard;
use Modules\ZeroPayModule\Filament\Resources\ZeroPayBankDepositResource;
use Modules\ZeroPayModule\Filament\Resources\ZeroPaySessionResource;
use Modules\ZeroPayModule\Filament\Resources\ZeroPayTransactionResource;
use Modules\ZeroPayModule\Filament\Widgets\ZeroPayGatewayBreakdownWidget;
use Modules\ZeroPayModule\Filament\Widgets\ZeroPayRecentSessionsWidget;
use Modules\ZeroPayModule\Filament\Widgets\ZeroPayRevenueChartWidget;
use Modules\ZeroPayModule\Filament\Widgets\ZeroPayStatsWidget;

class ZeroPayModulePlugin implements Plugin
{
    public static function make(): static
    {
        return app(static::class);
    }

    public function getId(): string
    {
        return 'zeropay-module';
    }

    public function register(Panel $panel): void
    {
        $panel->resources([
            ZeroPaySessionResource::class,
            ZeroPayTransactionResource::class,
            ZeroPayBankDepositResource::class,
        ]);

        $panel->pages([
            ZeroPayDashboard::class,
            ZeroPayControlPanel::class,
        ]);

        $panel->widgets([
            ZeroPayStatsWidget::class,
            ZeroPayRevenueChartWidget::class,
            ZeroPayGatewayBreakdownWidget::class,
            ZeroPayRecentSessionsWidget::class,
        ]);
    }

    public function boot(Panel $panel): void
    {
        //
    }
}
