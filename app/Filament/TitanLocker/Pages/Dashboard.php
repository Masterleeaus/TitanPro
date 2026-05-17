<?php

namespace App\Filament\TitanLocker\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-lock-closed';

    protected static ?string $navigationLabel = 'Home';

    protected static ?string $title = 'TitanLocker';

    protected static ?int $navigationSort = 1;
}
