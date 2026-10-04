<?php

namespace Modules\QuoteEngine\Filament\Pages;

use Filament\Pages\Page;

class QuoteBuilderPage extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Quote Builder';
    protected static string|\UnitEnum|null $navigationGroup = 'Estimating';
    protected static ?int $navigationSort = 10;
    protected string $view = 'quoteengine::pages.quotebuilderpage';

    public function getTitle(): string
    {
        return 'Quote Builder';
    }
}
