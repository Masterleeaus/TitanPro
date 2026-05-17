<?php

namespace App\Filament\SkillGapAnalyzer\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $navigationLabel = 'Home';

    protected static ?string $title = 'SKILL GAP ANALYZER';

    protected static ?int $navigationSort = 1;
}
