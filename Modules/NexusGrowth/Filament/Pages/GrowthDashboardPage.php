<?php

namespace Modules\NexusGrowth\Filament\Pages;

use Filament\Pages\Page;

class GrowthDashboardPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-arrow-trending-up';
    protected static ?string $navigationLabel = 'Growth Dashboard';
    protected static ?string $navigationGroup = 'Intelligence';
    protected static ?int $navigationSort = 10;
    protected static string $view = 'nexusgrowth::pages.growthdashboardpage';

    public function getTitle(): string
    {
        return 'Growth Dashboard';
    }
}
