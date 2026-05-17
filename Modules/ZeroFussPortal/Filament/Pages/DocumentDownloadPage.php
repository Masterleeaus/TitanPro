<?php

namespace Modules\ZeroFussPortal\Filament\Pages;

use Filament\Pages\Page;

class DocumentDownloadPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-arrow-down-tray';
    protected static ?string $navigationLabel = 'Document Downloads';
    protected static ?string $navigationGroup = 'Portal';
    protected static ?int $navigationSort = 40;
    protected static string $view = 'zerofussportal::pages.documentdownloadpage';

    public function getTitle(): string
    {
        return 'Document Downloads';
    }
}
