<?php

namespace Modules\TitanSoloDash\Filament\Pages;

use Filament\Pages\Page;

class SoloSettingsPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-cog';
    protected static ?string $navigationLabel = 'My Settings';
    protected static ?string $navigationGroup = 'Command Centre';
    protected static ?int $navigationSort = 40;
    protected static string $view = 'titansolodash::pages.solosettingspage';

    public function getTitle(): string
    {
        return 'My Settings';
    }
}
