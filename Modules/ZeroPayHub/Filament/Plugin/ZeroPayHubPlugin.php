<?php

namespace Modules\ZeroPayHub\Filament\Plugin;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Modules\ZeroPayHub\Filament\Pages\GatewayConfigPage;
use Modules\ZeroPayHub\Filament\Pages\PaymentPlansPage;
use Modules\ZeroPayHub\Filament\Pages\RefundManagerPage;
use Modules\ZeroPayHub\Filament\Pages\PayoutReportsPage;

class ZeroPayHubPlugin implements Plugin
{
    public function getId(): string
    {
        return 'zeropay-native';
    }

    public function register(Panel $panel): void
    {
        $panel->pages([
            GatewayConfigPage::class,
            PaymentPlansPage::class,
            RefundManagerPage::class,
            PayoutReportsPage::class,
        ]);
    }

    public function boot(Panel $panel): void {}

    public static function make(): static
    {
        return app(static::class);
    }
}
