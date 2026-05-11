<?php

namespace Modules\ZeroPayHub\Filament\Pages;

use Filament\Pages\Page;

class PaymentPlansPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-queue-list';
    protected static ?string $navigationLabel = 'Payment Plans';
    protected static ?string $navigationGroup = 'Payments';
    protected static ?int $navigationSort = 20;
    protected static string $view = 'zeropayhub::pages.paymentplanspage';

    public function getTitle(): string
    {
        return 'Payment Plans';
    }
}
