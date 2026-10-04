<?php

namespace Modules\TitanSoloDash\Filament\Pages;

use Filament\Pages\Page;

class SoloKpiPage extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-chart-pie';
    protected static ?string $navigationLabel = 'My KPIs';
    protected static string|\UnitEnum|null $navigationGroup = 'Command Centre';
    protected static ?int $navigationSort = 30;
    protected string $view = 'titansolodash::pages.solokpipage';

    public function getTitle(): string
    {
        return 'My KPIs';
    }
}
