<?php

namespace App\Filament\OnTheJobTrainingAssistant\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationLabel = 'Home';

    protected static ?string $title = 'ON THE JOB TRAINING ASSISTANT';

    protected static ?int $navigationSort = 1;
}
