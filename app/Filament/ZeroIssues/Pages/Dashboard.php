<?php

namespace App\Filament\ZeroIssues\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-exclamation-triangle';

    protected static ?string $navigationLabel = 'Home';

    protected static ?string $title = 'ZeroIssues';

    protected static ?int $navigationSort = 1;
}
