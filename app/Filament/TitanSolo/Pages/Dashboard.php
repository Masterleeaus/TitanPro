<?php

namespace App\Filament\TitanSolo\Pages;

use App\Filament\TitanSolo\Widgets\SoloOverviewWidget;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-home';

    protected static ?string $title = 'TitanSolo Dashboard';

    protected static ?string $navigationLabel = 'Dashboard';

    public function getWidgets(): array
    {
        return [
            SoloOverviewWidget::class,
        ];
    }
}
