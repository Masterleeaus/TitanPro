<?php

namespace App\Filament\QuoteAssistant\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-chat-bubble-bottom-center-text';

    protected static ?string $navigationLabel = 'Home';

    protected static ?string $title = 'QUOTE ASSISTANT';

    protected static ?int $navigationSort = 1;
}
