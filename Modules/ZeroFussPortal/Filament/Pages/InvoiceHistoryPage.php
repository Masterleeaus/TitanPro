<?php

namespace Modules\ZeroFussPortal\Filament\Pages;

use Filament\Pages\Page;

class InvoiceHistoryPage extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Invoice History';
    protected static string|\UnitEnum|null $navigationGroup = 'Portal';
    protected static ?int $navigationSort = 30;
    protected string $view = 'zerofussportal::pages.invoicehistorypage';

    public function getTitle(): string
    {
        return 'Invoice History';
    }
}
