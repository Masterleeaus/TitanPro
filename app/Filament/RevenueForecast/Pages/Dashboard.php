<?php

namespace App\Filament\RevenueForecast\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-presentation-chart-line';

    protected static ?string $navigationLabel = 'Home';

    protected static ?string $title = 'REVENUE FORECAST';

    protected static ?int $navigationSort = 1;
}
