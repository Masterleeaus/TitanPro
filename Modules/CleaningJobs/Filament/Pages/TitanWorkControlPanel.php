<?php

namespace Modules\CleaningJobs\Filament\Pages;

use Filament\Pages\Page;

/**
 * TitanWorkControlPanel is the single entry point for the TitanWork module.
 *
 * This page consolidates metrics, widgets, shortcuts, settings and the
 * tabbed tables defined in the ControlPanel namespace. Implementation
 * details of rendering are deferred to frontend components; this class
 * simply defines the route and basic layout for Filament.
 */
class TitanWorkControlPanel extends Page
{
    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-cog-6-tooth';

    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }

    public function getView(): string
    {
        return 'titanwork::control-panel';
    }


    public static function getNavigationLabel(): string
    {
        return 'TitanWork';
    }
}