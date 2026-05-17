<?php

namespace App\Filament\TitanGo\Pages;

use Filament\Pages\Page;

class AppSettings extends Page
{
    protected static bool $shouldRegisterNavigation = false;
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationLabel = 'Settings';
    protected static ?string $title = 'Settings';
    protected static ?int $navigationSort = 99;
    protected static ?string $slug = 'settings';
    protected string $view = 'filament.titango.pages.hidden';

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }
}
