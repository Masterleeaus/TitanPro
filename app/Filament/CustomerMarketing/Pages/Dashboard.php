<?php

namespace App\Filament\CustomerMarketing\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-envelope-open';

    protected static ?string $navigationLabel = 'Home';

    protected static ?string $title = 'CUSTOMER MARKETING';

    protected static ?int $navigationSort = 1;
}
