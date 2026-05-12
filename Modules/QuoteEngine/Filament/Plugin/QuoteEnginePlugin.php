<?php

namespace Modules\QuoteEngine\Filament\Plugin;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Modules\QuoteEngine\Filament\Pages\QuoteBuilderPage;
use Modules\QuoteEngine\Filament\Pages\RateCardManagerPage;
use Modules\QuoteEngine\Filament\Pages\QuoteApprovalsPage;
use Modules\QuoteEngine\Filament\Pages\QuoteAnalyticsPage;

class QuoteEnginePlugin implements Plugin
{
    public function getId(): string
    {
        return 'titanquotes-native';
    }

    public function register(Panel $panel): void
    {
        $panel->pages([
            QuoteBuilderPage::class,
            RateCardManagerPage::class,
            QuoteApprovalsPage::class,
            QuoteAnalyticsPage::class,
        ]);
    }

    public function boot(Panel $panel): void {}

    public static function make(): static
    {
        return app(static::class);
    }
}
