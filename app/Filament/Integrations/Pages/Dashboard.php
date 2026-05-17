<?php

namespace App\Filament\Integrations\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-puzzle-piece';

    protected static ?string $navigationLabel = 'Home';

    protected static ?string $title = 'INTEGRATIONS';

    protected static ?int $navigationSort = 1;
}
