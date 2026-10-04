<?php

namespace Modules\ZeroFussPortal\Filament\Pages;

use Filament\Pages\Page;

class CustomerDashboardPage extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-home';
    protected static ?string $navigationLabel = 'My Dashboard';
    protected static string|\UnitEnum|null $navigationGroup = 'Portal';
    protected static ?int $navigationSort = 10;
    protected string $view = 'zerofussportal::pages.customerdashboardpage';

    public function getTitle(): string
    {
        return 'My Dashboard';
    }
}
