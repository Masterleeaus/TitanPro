<?php

namespace App\Filament\Training\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-book-open';

    protected static ?string $navigationLabel = 'Home';

    protected static ?string $title = 'TRAINING';

    protected static ?int $navigationSort = 1;
}
