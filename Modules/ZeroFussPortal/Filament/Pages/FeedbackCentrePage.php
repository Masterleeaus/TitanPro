<?php

namespace Modules\ZeroFussPortal\Filament\Pages;

use Filament\Pages\Page;

class FeedbackCentrePage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-star';
    protected static ?string $navigationLabel = 'Feedback & Ratings';
    protected static ?string $navigationGroup = 'Portal';
    protected static ?int $navigationSort = 40;
    protected static string $view = 'zerofussportal::pages.feedbackcentrepage';

    public function getTitle(): string
    {
        return 'Feedback & Ratings';
    }
}
