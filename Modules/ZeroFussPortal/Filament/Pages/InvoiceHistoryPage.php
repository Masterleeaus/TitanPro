<?php

namespace Modules\ZeroFussPortal\Filament\Pages;

use Filament\Pages\Page;

class InvoiceHistoryPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Invoice History';
    protected static ?string $navigationGroup = 'Portal';
    protected static ?int $navigationSort = 30;
    protected static string $view = 'zerofussportal::pages.invoicehistorypage';

    public function getTitle(): string
    {
        return 'Invoice History';
    }
}
