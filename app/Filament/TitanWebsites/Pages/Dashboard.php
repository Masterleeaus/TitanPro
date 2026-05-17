<?php

namespace App\Filament\TitanWebsites\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-globe-alt';

    protected static ?string $navigationLabel = 'Home';

    protected static ?string $title = 'TITAN WEBSITES';

    protected static ?int $navigationSort = 1;
}
