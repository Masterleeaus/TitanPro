<?php

namespace Modules\TitanSoloDash\Filament\Pages;

use Filament\Pages\Page;

class SoloSettingsPage extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-cog';
    protected static ?string $navigationLabel = 'My Settings';
    protected static string|\UnitEnum|null $navigationGroup = 'Command Centre';
    protected static ?int $navigationSort = 40;
    protected string $view = 'titansolodash::pages.solosettingspage';

    public function getTitle(): string
    {
        return 'My Settings';
    }
}
