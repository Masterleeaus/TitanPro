<?php

namespace Modules\ExampleModule\Filament\Pages;

use Filament\Pages\Page;

class ExampleModulePage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';
    protected static ?string $navigationGroup = 'Operations';
    protected static ?string $navigationLabel = 'Example Module';
    protected static ?string $title = 'Example Module';
    protected static ?int $navigationSort = 100;
    protected static string $view = 'example-module::filament.pages.example-module-page';

    public static function canAccess(): bool
    {
        return auth()->user()?->can('example.view') ?? false;
    }

    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }
}
