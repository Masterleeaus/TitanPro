<?php

namespace App\Filament\BookingsAssistant\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-calendar-date-range';

    protected static ?string $navigationLabel = 'Home';

    protected static ?string $title = 'BOOKINGS ASSISTANT';

    protected static ?int $navigationSort = 1;
}
