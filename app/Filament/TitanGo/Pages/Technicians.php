<?php

namespace App\Filament\TitanGo\Pages;

use Filament\Pages\Page;

class Technicians extends Page
{
    protected static bool $shouldRegisterNavigation = false;
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'Technicians';
    protected static ?string $title = 'Technicians';
    protected static ?int $navigationSort = 99;
    protected static ?string $slug = 'technicians';
    protected string $view = 'filament.titango.pages.hidden';

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }
}
