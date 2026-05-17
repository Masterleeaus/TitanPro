<?php

namespace App\Filament\DocsAndContracts\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'Home';

    protected static ?string $title = 'DOCS & CONTRACTS';

    protected static ?int $navigationSort = 1;
}
