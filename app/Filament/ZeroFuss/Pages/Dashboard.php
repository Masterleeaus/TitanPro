<?php

namespace App\Filament\ZeroFuss\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-home';

    protected static ?string $title = 'ZeroFuss Dashboard';

    protected static ?string $navigationLabel = 'Dashboard';
}
