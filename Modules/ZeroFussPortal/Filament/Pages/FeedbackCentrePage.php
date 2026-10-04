<?php

namespace Modules\ZeroFussPortal\Filament\Pages;

use Filament\Pages\Page;

class FeedbackCentrePage extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-star';
    protected static ?string $navigationLabel = 'Feedback & Ratings';
    protected static string|\UnitEnum|null $navigationGroup = 'Portal';
    protected static ?int $navigationSort = 60;
    protected string $view = 'zerofussportal::pages.feedbackcentrepage';

    public function getTitle(): string
    {
        return 'Feedback & Ratings';
    }
}
