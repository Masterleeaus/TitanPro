<?php

namespace App\Filament\GroundZero\Pages;

/**
 * Compatibility page for earlier GroundZero builds that registered or cached
 * filament.groundzero.pages.titan-work-control-panel in navigation.
 *
 * Keep this route alive while the visible GroundZero entry point is Dashboard.
 */
class TitanWorkControlPanel extends Dashboard
{
    protected static ?string $slug = 'titan-work-control-panel';
    protected static ?string $navigationLabel = 'Workspace';
    protected static ?string $title = 'GroundZero';
    protected static bool $shouldRegisterNavigation = false;
}
