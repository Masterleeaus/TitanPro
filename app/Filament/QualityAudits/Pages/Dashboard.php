<?php

namespace App\Filament\QualityAudits\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static ?string $navigationLabel = 'Home';

    protected static ?string $title = 'QUALITY AUDITS';

    protected static ?int $navigationSort = 1;
}
