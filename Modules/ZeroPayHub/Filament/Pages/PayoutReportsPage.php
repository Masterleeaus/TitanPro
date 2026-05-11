<?php

namespace Modules\ZeroPayHub\Filament\Pages;

use Filament\Pages\Page;

class PayoutReportsPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationLabel = 'Payout Reports';
    protected static ?string $navigationGroup = 'Payments';
    protected static ?int $navigationSort = 40;
    protected static string $view = 'zeropayhub::pages.payoutreportspage';

    public function getTitle(): string
    {
        return 'Payout Reports';
    }
}
