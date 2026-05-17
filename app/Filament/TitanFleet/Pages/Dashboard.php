<?php

namespace App\Filament\TitanFleet\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-truck';

    protected static ?string $navigationLabel = 'Home';

    protected static ?string $title = 'TITAN FLEET';

    protected static ?int $navigationSort = 1;
}
