<?php

namespace App\Filament\ChemicalsAssistant\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-beaker';

    protected static ?string $navigationLabel = 'Home';

    protected static ?string $title = 'CHEMICALS ASSISTANT';

    protected static ?int $navigationSort = 1;
}
