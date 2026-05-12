<?php

namespace Modules\ZeroFussPortal\Filament\Plugin;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Modules\ZeroFussPortal\Filament\Pages\CustomerDashboardPage;
use Modules\ZeroFussPortal\Filament\Pages\MyBookingsPage;
use Modules\ZeroFussPortal\Filament\Pages\MyInvoicesPage;
use Modules\ZeroFussPortal\Filament\Pages\FeedbackCentrePage;

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
            MyBookingsPage::class,
            MyInvoicesPage::class,
            FeedbackCentrePage::class,
        ]);
    }

    public function boot(Panel $panel): void {}

    public static function make(): static
    {
        return app(static::class);
    }
}
