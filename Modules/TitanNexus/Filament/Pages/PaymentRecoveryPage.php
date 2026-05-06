<?php

namespace Modules\TitanNexus\Filament\Pages;

use Filament\Pages\Page;

class PaymentRecoveryPage extends Page
{
    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-credit-card';
    protected static \UnitEnum|string|null $navigationGroup = 'Titan Nexus';
    protected static ?string $navigationLabel = 'Payment Recovery';
    protected static ?string $title = 'Payment Recovery';
    protected static ?int $navigationSort = 40;

    protected string $view = 'titan-nexus::filament.pages.nexus-placeholder-page';

    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }
}
