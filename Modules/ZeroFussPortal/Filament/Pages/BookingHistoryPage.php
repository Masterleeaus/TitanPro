<?php

namespace Modules\ZeroFussPortal\Filament\Pages;

use Filament\Pages\Page;

class BookingHistoryPage extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-calendar';
    protected static ?string $navigationLabel = 'Booking History';
    protected static string|\UnitEnum|null $navigationGroup = 'Portal';
    protected static ?int $navigationSort = 20;
    protected string $view = 'zerofussportal::pages.bookinghistorypage';

    public function getTitle(): string
    {
        return 'Booking History';
    }
}
