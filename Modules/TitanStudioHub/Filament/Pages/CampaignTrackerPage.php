<?php

namespace Modules\TitanStudioHub\Filament\Pages;

use Filament\Pages\Page;

class CampaignTrackerPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-megaphone';
    protected static ?string $navigationLabel = 'Campaign Tracker';
    protected static ?string $navigationGroup = 'Studio';
    protected static ?int $navigationSort = 30;
    protected static string $view = 'titanstudiohub::pages.campaigntrackerpage';

    public function getTitle(): string
    {
        return 'Campaign Tracker';
    }
}
