<?php

namespace Modules\ZeroPayHub\Filament\Pages;

use Filament\Pages\Page;

class GatewayConfigPage extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-credit-card';
    protected static ?string $navigationLabel = 'Gateway Config';
    protected static string|\UnitEnum|null $navigationGroup = 'Payments';
    protected static ?int $navigationSort = 10;
    protected string $view = 'zeropayhub::pages.gatewayconfigpage';

    public function getTitle(): string
    {
        return 'Gateway Config';
    }
}
