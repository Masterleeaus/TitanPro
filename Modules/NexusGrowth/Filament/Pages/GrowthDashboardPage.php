<?php

namespace Modules\NexusGrowth\Filament\Pages;

use Filament\Pages\Page;

class GrowthDashboardPage extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-arrow-trending-up';
    protected static ?string $navigationLabel = 'Growth Dashboard';
    protected static string|\UnitEnum|null $navigationGroup = 'Intelligence';
    protected static ?int $navigationSort = 10;
    protected string $view = 'nexusgrowth::pages.growthdashboardpage';

    public function getTitle(): string
    {
        return 'Growth Dashboard';
    }
}
