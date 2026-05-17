<?php

namespace App\Filament\TitanGo\Pages;

use Filament\Pages\Page;

class Safety extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-shield-check';

    protected static string|\UnitEnum|null $navigationGroup = 'TitanGo';
    protected static ?string $navigationLabel = 'Safety';
    protected static ?string $title = 'Safety';
    protected static ?int $navigationSort = 6;
    protected static ?string $slug = 'safety';
    protected string $view = 'filament.titango.pages.safety';

    protected function getViewData(): array
    {
        return [
            'helpEndpoint' => route('titango.safety.help', absolute: false),
            'unsafeSiteEndpoint' => route('titango.safety.unsafe-site', absolute: false),
        ];
    }
}
