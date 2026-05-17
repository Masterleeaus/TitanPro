<?php

namespace App\Filament\TitanSocial\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-share';

    protected static ?string $navigationLabel = 'Home';

    protected static ?string $title = 'Titan Social';

    protected static ?int $navigationSort = 1;
}
