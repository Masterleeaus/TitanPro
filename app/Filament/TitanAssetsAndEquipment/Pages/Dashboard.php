<?php

namespace App\Filament\TitanAssetsAndEquipment\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-cube-transparent';

    protected static ?string $navigationLabel = 'Home';

    protected static ?string $title = 'TITAN ASSETS & EQUIPMENT';

    protected static ?int $navigationSort = 1;
}
