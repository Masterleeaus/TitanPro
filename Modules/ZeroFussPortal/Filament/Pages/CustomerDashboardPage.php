<?php

namespace Modules\ZeroFussPortal\Filament\Pages;

use Filament\Pages\Page;

class CustomerDashboardPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static ?string $navigationLabel = 'My Dashboard';
    protected static ?string $navigationGroup = 'Portal';
    protected static ?int $navigationSort = 10;
    protected static string $view = 'zerofussportal::pages.customerdashboardpage';

    public function getTitle(): string
    {
        return 'My Dashboard';
    }
}
