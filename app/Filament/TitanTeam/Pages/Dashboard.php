<?php

namespace App\Filament\TitanTeam\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel = 'Home';

    protected static ?string $title = 'Titan Team';

    protected static ?int $navigationSort = 1;
}
