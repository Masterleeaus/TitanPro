<?php

namespace Modules\ZeroFussPortal\Filament\Pages;

use Filament\Pages\Page;

class MyBookingsPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static ?string $navigationLabel = 'My Bookings';
    protected static ?string $navigationGroup = 'Portal';
    protected static ?int $navigationSort = 20;
    protected static string $view = 'zerofussportal::pages.mybookingspage';

    public function getTitle(): string
    {
        return 'My Bookings';
    }
}
