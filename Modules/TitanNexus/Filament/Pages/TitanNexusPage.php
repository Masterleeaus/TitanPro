<?php

namespace Modules\TitanNexus\Filament\Pages;

use Filament\Pages\Page;

class TitanNexusPage extends Page
{
    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-squares-2x2';
    protected static \UnitEnum|string|null $navigationGroup = 'Operations';
    protected static ?string $navigationLabel = 'Titan Nexus';
    protected static ?string $title = 'Titan Nexus';
    protected static ?int $navigationSort = 100;

    protected string $view = 'titan-nexus::filament.pages.nexus-placeholder-page';

    public static function canAccess(): bool
    {
        return auth()->user()?->can('titan_nexus.view') ?? true;
    }

    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }
}
