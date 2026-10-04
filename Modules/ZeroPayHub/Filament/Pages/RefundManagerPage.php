<?php

namespace Modules\ZeroPayHub\Filament\Pages;

use Filament\Pages\Page;

class RefundManagerPage extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-arrow-uturn-left';
    protected static ?string $navigationLabel = 'Refund Manager';
    protected static string|\UnitEnum|null $navigationGroup = 'Payments';
    protected static ?int $navigationSort = 30;
    protected string $view = 'zeropayhub::pages.refundmanagerpage';

    public function getTitle(): string
    {
        return 'Refund Manager';
    }
}
