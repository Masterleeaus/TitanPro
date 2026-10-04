<?php

namespace Modules\TitanGoField\Filament\Pages;

use Filament\Pages\Page;

class CheckInPage extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-map-pin';
    protected static ?string $navigationLabel = 'Check In / Out';
    protected static string|\UnitEnum|null $navigationGroup = 'Field';
    protected static ?int $navigationSort = 20;
    protected string $view = 'titangofield::pages.checkinpage';

    public function getTitle(): string
    {
        return 'Check In / Out';
    }
}
