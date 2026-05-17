<?php

namespace Modules\ZeroFussPortal\Filament\Plugin;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Modules\ZeroFussPortal\Filament\Pages\BookingHistoryPage;
use Modules\ZeroFussPortal\Filament\Pages\CustomerDashboardPage;
use Modules\ZeroFussPortal\Filament\Pages\DocumentDownloadPage;
use Modules\ZeroFussPortal\Filament\Pages\InvoiceHistoryPage;
use Modules\ZeroFussPortal\Filament\Pages\ReferralPage;

class ZeroFussPortalPlugin implements Plugin
{
    public function getId(): string
    {
        return 'zerofuss-native';
    }

    public function register(Panel $panel): void
    {
        $panel->pages([
            CustomerDashboardPage::class,
            BookingHistoryPage::class,
            InvoiceHistoryPage::class,
            DocumentDownloadPage::class,
            ReferralPage::class,
        ]);
    }

    public function boot(Panel $panel): void
    {
    }

    public static function make(): static
    {
        return app(static::class);
    }
}
