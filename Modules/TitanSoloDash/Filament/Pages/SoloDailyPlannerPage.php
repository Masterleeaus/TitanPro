<?php

namespace Modules\TitanSoloDash\Filament\Pages;

use Filament\Pages\Page;

class SoloDailyPlannerPage extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?string $navigationLabel = 'Daily Planner';
    protected static string|\UnitEnum|null $navigationGroup = 'Command Centre';
    protected static ?int $navigationSort = 10;
    protected string $view = 'titansolodash::pages.solodailyplannerpage';

    public function getTitle(): string
    {
        return 'Daily Planner';
    }
}
