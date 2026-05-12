<?php

namespace Modules\NexusGrowth\Filament\Pages;

use Filament\Pages\Page;

class RoiReportPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar-square';
    protected static ?string $navigationLabel = 'ROI Reports';
    protected static ?string $navigationGroup = 'Intelligence';
    protected static ?int $navigationSort = 40;
    protected static string $view = 'nexusgrowth::pages.roireportpage';

    public function getTitle(): string
    {
        return 'ROI Reports';
    }
}
