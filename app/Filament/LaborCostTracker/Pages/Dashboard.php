<?php

namespace App\Filament\LaborCostTracker\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-clock';

    protected static ?string $navigationLabel = 'Home';

    protected static ?string $title = 'LABOR COST TRACKER';

    protected static ?int $navigationSort = 1;
}
