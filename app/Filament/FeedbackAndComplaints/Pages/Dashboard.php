<?php

namespace App\Filament\FeedbackAndComplaints\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-face-smile';

    protected static ?string $navigationLabel = 'Home';

    protected static ?string $title = 'FEEDBACK & COMPLAINTS';

    protected static ?int $navigationSort = 1;
}
