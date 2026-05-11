<?php

namespace Modules\QuoteEngine\Filament\Pages;

use Filament\Pages\Page;

class RateCardManagerPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';
    protected static ?string $navigationLabel = 'Rate Cards';
    protected static ?string $navigationGroup = 'Estimating';
    protected static ?int $navigationSort = 20;
    protected static string $view = 'quoteengine::pages.ratecardmanagerpage';

    public function getTitle(): string
    {
        return 'Rate Cards';
    }
}
