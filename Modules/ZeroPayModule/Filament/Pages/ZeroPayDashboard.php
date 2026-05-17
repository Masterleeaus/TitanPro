<?php

namespace Modules\ZeroPayModule\Filament\Pages;

use Filament\Pages\Page;
use Modules\ZeroPayModule\Filament\Widgets\ZeroPayGatewayBreakdownWidget;
use Modules\ZeroPayModule\Filament\Widgets\ZeroPayRecentSessionsWidget;
use Modules\ZeroPayModule\Filament\Widgets\ZeroPayRevenueChartWidget;
use Modules\ZeroPayModule\Filament\Widgets\ZeroPayStatsWidget;

class ZeroPayDashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $navigationGroup = 'ZeroPay';

    protected static ?string $navigationLabel = 'Dashboard';

    protected static ?string $title = 'ZeroPay Dashboard';

    protected static ?int $navigationSort = 0;

    protected static ?string $slug = 'zeropay';

    protected static string $view = 'zeropay-module::filament.pages.zeropay-kpi-dashboard';

    public function getHeaderWidgets(): array
    {
        return [
            ZeroPayStatsWidget::class,
            ZeroPayRevenueChartWidget::class,
            ZeroPayGatewayBreakdownWidget::class,
            ZeroPayRecentSessionsWidget::class,
        ];
    }

    public function getHeaderWidgetsColumns(): int|array
    {
        return 2;
    }

    public static function canAccess(): bool
    {
        return auth()->user()?->can('zeropay.view') ?? false;
    }
}
