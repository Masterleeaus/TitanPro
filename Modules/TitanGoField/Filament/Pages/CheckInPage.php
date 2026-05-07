<?php

namespace Modules\TitanGoField\Filament\Pages;

use Filament\Pages\Page;

class CheckInPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-map-pin';
    protected static ?string $navigationLabel = 'Check In / Out';
    protected static ?string $navigationGroup = 'Field';
    protected static ?int $navigationSort = 20;
    protected static string $view = 'titangofield::pages.checkinpage';

    public function getTitle(): string
    {
        return 'Check In / Out';
    }
}
