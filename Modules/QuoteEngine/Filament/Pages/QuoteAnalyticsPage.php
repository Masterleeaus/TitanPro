<?php

namespace Modules\QuoteEngine\Filament\Pages;

use Filament\Pages\Page;

class QuoteAnalyticsPage extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationLabel = 'Quote Analytics';
    protected static string|\UnitEnum|null $navigationGroup = 'Estimating';
    protected static ?int $navigationSort = 40;
    protected string $view = 'quoteengine::pages.quoteanalyticspage';

    public function getTitle(): string
    {
        return 'Quote Analytics';
    }
}
