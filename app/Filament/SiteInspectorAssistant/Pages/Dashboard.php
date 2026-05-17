<?php

namespace App\Filament\SiteInspectorAssistant\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-camera';

    protected static ?string $navigationLabel = 'Home';

    protected static ?string $title = 'SITE INSPECTOR ASSISTANT';

    protected static ?int $navigationSort = 1;
}
