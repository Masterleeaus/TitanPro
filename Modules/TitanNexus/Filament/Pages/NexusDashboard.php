<?php

namespace Modules\TitanNexus\Filament\Pages;

use Filament\Pages\Page;

class NexusDashboard extends Page
{
    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-chart-bar-square';
    protected static \UnitEnum|string|null $navigationGroup = 'Titan Nexus';
    protected static ?string $navigationLabel = 'Nexus Dashboard';
    protected static ?string $title = 'Nexus Dashboard';
    protected static ?int $navigationSort = 10;

    protected string $view = 'titan-nexus::filament.pages.nexus-placeholder-page';

    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }
}
