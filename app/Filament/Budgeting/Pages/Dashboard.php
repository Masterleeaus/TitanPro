<?php

namespace App\Filament\Budgeting\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-chart-pie';

    protected static ?string $navigationLabel = 'Home';

    protected static ?string $title = 'BUDGETING';

    protected static ?int $navigationSort = 1;
}
