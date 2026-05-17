<?php

namespace App\Filament\Seo\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-magnifying-glass-circle';

    protected static ?string $navigationLabel = 'Home';

    protected static ?string $title = 'SEO';

    protected static ?int $navigationSort = 1;
}
