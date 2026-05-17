<?php

namespace App\Filament\TitanSuppliersAndInventory\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-archive-box';

    protected static ?string $navigationLabel = 'Home';

    protected static ?string $title = 'TITAN SUPPLIERS & INVENTORY';

    protected static ?int $navigationSort = 1;
}
