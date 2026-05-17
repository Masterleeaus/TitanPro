<?php

namespace App\Filament\JobProfitabilityAnalyzer\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-calculator';

    protected static ?string $navigationLabel = 'Home';

    protected static ?string $title = 'JOB PROFITABILITY ANALYZER';

    protected static ?int $navigationSort = 1;
}
