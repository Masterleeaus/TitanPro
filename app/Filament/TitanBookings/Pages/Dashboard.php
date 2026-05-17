<?php

namespace App\Filament\TitanBookings\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $navigationLabel = 'Home';

    protected static ?string $title = 'TITAN BOOKINGS';

    protected static ?int $navigationSort = 1;
}
