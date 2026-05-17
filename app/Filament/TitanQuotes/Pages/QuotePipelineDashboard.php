<?php

namespace App\Filament\TitanQuotes\Pages;

use Filament\Pages\Page;

class QuotePipelineDashboard extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-document-text';

    protected static string|\UnitEnum|null $navigationGroup = 'Titan Quotes';

    protected string $view = 'filament.titanquotes.pages.quote-pipeline-dashboard';
}
