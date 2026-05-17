<?php

namespace App\Filament\TitanNexus\Pages;

use Filament\Pages\Page;

class MarketingCampaigns extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-megaphone';

    protected static string|\UnitEnum|null $navigationGroup = 'Outreach';

    protected static ?string $navigationLabel = 'Outreach Overview';

    protected static ?int $navigationSort = 23;

    protected static ?string $title = 'Outreach Overview';

    protected string $view = 'filament.titan-nexus.pages.marketing-campaigns';
}
