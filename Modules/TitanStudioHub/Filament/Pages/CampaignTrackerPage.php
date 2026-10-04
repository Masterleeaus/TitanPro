<?php

namespace Modules\TitanStudioHub\Filament\Pages;

use Filament\Pages\Page;

class CampaignTrackerPage extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-megaphone';
    protected static ?string $navigationLabel = 'Campaign Tracker';
    protected static string|\UnitEnum|null $navigationGroup = 'Studio';
    protected static ?int $navigationSort = 30;
    protected string $view = 'titanstudiohub::pages.campaigntrackerpage';

    public function getTitle(): string
    {
        return 'Campaign Tracker';
    }
}
