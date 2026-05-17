<?php

namespace App\Filament\TitanGo\Pages;

use Filament\Pages\Page;

class CleanerLocation extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-map-pin';

    protected static string|\UnitEnum|null $navigationGroup = 'TitanGo';

    protected static ?string $navigationLabel = 'Location';

    protected static ?string $title = 'Location';

    protected static ?int $navigationSort = 7;

    protected static ?string $slug = 'location';

    protected string $view = 'filament.titango.pages.cleaner-location';

    protected function getViewData(): array
    {
        return [
            'endpoint' => url('/api/technician/location'),
        ];
    }
}
