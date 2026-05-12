<?php

namespace Modules\ZeroFussPortal\Filament\Pages;

use Filament\Pages\Page;

class ReferralPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-user-plus';
    protected static ?string $navigationLabel = 'Referrals';
    protected static ?string $navigationGroup = 'Portal';
    protected static ?int $navigationSort = 50;
    protected static string $view = 'zerofussportal::pages.referralpage';

    public function getTitle(): string
    {
        return 'Referrals';
    }
}
