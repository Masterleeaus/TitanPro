<?php

namespace App\Filament\ComplianceAssistant\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-scale';

    protected static ?string $navigationLabel = 'Home';

    protected static ?string $title = 'COMPLIANCE ASSISTANT';

    protected static ?int $navigationSort = 1;
}
