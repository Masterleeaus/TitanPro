<?php

namespace Modules\ZeroFussPortal\Filament\Pages;

use Filament\Pages\Page;

class DocumentDownloadPage extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-arrow-down-tray';
    protected static ?string $navigationLabel = 'Document Downloads';
    protected static string|\UnitEnum|null $navigationGroup = 'Portal';
    protected static ?int $navigationSort = 40;
    protected string $view = 'zerofussportal::pages.documentdownloadpage';

    public function getTitle(): string
    {
        return 'Document Downloads';
    }
}
