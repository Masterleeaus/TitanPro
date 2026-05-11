<?php

namespace App\Filament\TitanNexus\Pages;

use Filament\Pages\Page;

class MarketingCampaigns extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-megaphone';

    protected static ?string $navigationLabel = 'Marketing Campaigns';

    protected static ?int $navigationSort = 40;

    protected string $view = 'filament.titan-nexus.pages.marketing-campaigns';
}
