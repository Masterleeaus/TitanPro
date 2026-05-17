<?php

namespace App\Filament\LeadScorer\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-funnel';

    protected static ?string $navigationLabel = 'Home';

    protected static ?string $title = 'LEAD SCORER';

    protected static ?int $navigationSort = 1;
}
