<?php

namespace Modules\TitanNexus\Filament\Pages;

use Filament\Pages\Page;

class NexusControlPanelPage extends Page
{
    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-adjustments-horizontal';
    protected static \UnitEnum|string|null $navigationGroup = 'Titan Nexus';
    protected static ?string $navigationLabel = 'Nexus Control Panel';
    protected static ?string $title = 'Nexus Control Panel';
    protected static ?int $navigationSort = 20;

    protected string $view = 'titan-nexus::filament.pages.nexus-placeholder-page';

    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }
}
