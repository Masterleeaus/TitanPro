<?php

namespace App\Filament\ChurnEarlyWarning\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-bell-alert';

    protected static ?string $navigationLabel = 'Home';

    protected static ?string $title = 'CHURN EARLY WARNING';

    protected static ?int $navigationSort = 1;
}
