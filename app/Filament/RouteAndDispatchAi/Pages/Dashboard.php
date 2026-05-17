<?php

namespace App\Filament\RouteAndDispatchAi\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-map';

    protected static ?string $navigationLabel = 'Home';

    protected static ?string $title = 'ROUTE & DISPATCH AI';

    protected static ?int $navigationSort = 1;
}
