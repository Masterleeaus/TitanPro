<?php

namespace App\Filament\TitanZero\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-cpu-chip';

    protected static ?string $navigationLabel = 'Home';

    protected static ?string $title = 'Titan Zero';

    protected static ?int $navigationSort = 1;
}
