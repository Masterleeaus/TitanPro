<?php

namespace App\Filament\TitanMoney\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationLabel = 'Home';

    protected static ?string $title = 'Titan Money';

    protected static ?int $navigationSort = 1;
}
