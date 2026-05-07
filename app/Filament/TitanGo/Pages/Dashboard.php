<?php

namespace App\Filament\TitanGo\Pages;

use App\Filament\TitanGo\Widgets\ActiveJobsWidget;
use App\Filament\TitanGo\Widgets\PwaPreviewBridgeWidget;
use App\Filament\TitanGo\Widgets\SyncHealthWidget;
use App\Filament\TitanGo\Widgets\TechnicianActivityWidget;
use App\Filament\TitanGo\Widgets\TitanGoDashboardWidget;
use App\Filament\Widgets\CleanerLiveMap;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-device-phone-mobile';

    protected static ?string $title = 'TitanGo Dashboard';

    protected static ?string $navigationLabel = 'Dashboard';

    public function getWidgets(): array
    {
        return [
            TitanGoDashboardWidget::class,
            TechnicianActivityWidget::class,
            ActiveJobsWidget::class,
            CleanerLiveMap::class,
            SyncHealthWidget::class,
            PwaPreviewBridgeWidget::class,
        ];
    }
}
