<?php

namespace Modules\QuoteEngine\Filament\Pages;

use Filament\Pages\Page;

class QuoteBuilderPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Quote Builder';
    protected static ?string $navigationGroup = 'Estimating';
    protected static ?int $navigationSort = 10;
    protected static string $view = 'quoteengine::pages.quotebuilderpage';

    public function getTitle(): string
    {
        return 'Quote Builder';
    }
}
