<?php

namespace App\Filament\TitanEcho\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-phone';

    protected static ?string $navigationLabel = 'Home';

    protected static ?string $title = 'Titan Echo';

    protected static ?int $navigationSort = 1;
}
