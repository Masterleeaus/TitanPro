<?php

namespace App\Filament\TitanPayroll\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-receipt-percent';

    protected static ?string $navigationLabel = 'Home';

    protected static ?string $title = 'TITAN PAYROLL';

    protected static ?int $navigationSort = 1;
}
