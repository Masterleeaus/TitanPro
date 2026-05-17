<?php

namespace App\Filament\TitanGo\Pages;

use Filament\Pages\Page;

class Map extends Page
{
    protected static bool $shouldRegisterNavigation = false;
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-map';
    protected static ?string $navigationLabel = 'Map';
    protected static ?string $title = 'Map';
    protected static ?int $navigationSort = 99;
    protected static ?string $slug = 'map';
    protected string $view = 'filament.titango.pages.hidden';

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }
}
