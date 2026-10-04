<?php

namespace Modules\NexusGrowth\Filament\Pages;

use Filament\Pages\Page;

class RoiReportPage extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-chart-bar-square';
    protected static ?string $navigationLabel = 'ROI Reports';
    protected static string|\UnitEnum|null $navigationGroup = 'Intelligence';
    protected static ?int $navigationSort = 40;
    protected string $view = 'nexusgrowth::pages.roireportpage';

    public function getTitle(): string
    {
        return 'ROI Reports';
    }
}
