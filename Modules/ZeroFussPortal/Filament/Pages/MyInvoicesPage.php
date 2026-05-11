<?php

namespace Modules\ZeroFussPortal\Filament\Pages;

use Filament\Pages\Page;

class MyInvoicesPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'My Invoices';
    protected static ?string $navigationGroup = 'Portal';
    protected static ?int $navigationSort = 30;
    protected static string $view = 'zerofussportal::pages.myinvoicespage';

    public function getTitle(): string
    {
        return 'My Invoices';
    }
}
