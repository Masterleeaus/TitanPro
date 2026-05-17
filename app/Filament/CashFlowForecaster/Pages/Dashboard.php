<?php

namespace App\Filament\CashFlowForecaster\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-arrow-trending-up';

    protected static ?string $navigationLabel = 'Home';

    protected static ?string $title = 'CASH FLOW FORECASTER';

    protected static ?int $navigationSort = 1;
}
