<?php

namespace App\Filament\TitanPixel\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-swatch';

    protected static ?string $navigationLabel = 'Home';

    protected static ?string $title = 'Titan Pixel';

    protected static ?int $navigationSort = 1;
}
