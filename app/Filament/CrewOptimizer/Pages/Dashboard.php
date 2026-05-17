<?php

namespace App\Filament\CrewOptimizer\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel = 'Home';

    protected static ?string $title = 'CREW OPTIMIZER';

    protected static ?int $navigationSort = 1;
}
