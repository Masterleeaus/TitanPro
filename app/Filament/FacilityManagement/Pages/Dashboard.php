<?php

namespace App\Filament\FacilityManagement\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-building-office-2';

    protected static ?string $navigationLabel = 'Home';

    protected static ?string $title = 'FACILITY MANAGEMENT';

    protected static ?int $navigationSort = 1;
}
