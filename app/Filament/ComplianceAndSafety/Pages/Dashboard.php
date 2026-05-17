<?php

namespace App\Filament\ComplianceAndSafety\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-shield-exclamation';

    protected static ?string $navigationLabel = 'Home';

    protected static ?string $title = 'COMPLIANCE & SAFETY';

    protected static ?int $navigationSort = 1;
}
