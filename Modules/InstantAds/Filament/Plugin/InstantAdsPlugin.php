<?php

namespace Modules\InstantAds\Filament\Plugin;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Modules\InstantAds\Filament\Pages\AdSettingsPage;
use Modules\InstantAds\Filament\Pages\BatchVariantPage;
use Modules\InstantAds\Filament\Resources\AdCreativeResource;
use Modules\InstantAds\Filament\Widgets\CreativeLibraryWidget;

class InstantAdsPlugin implements Plugin
{
    public function getId(): string
    {
        return 'instant-ads';
    }

    public function register(Panel $panel): void
    {
        $panel
            ->resources([
                AdCreativeResource::class,
            ])
            ->pages([
                BatchVariantPage::class,
                AdSettingsPage::class,
            ])
            ->widgets([
                CreativeLibraryWidget::class,
            ]);
    }

    public function boot(Panel $panel): void {}

    public static function make(): static
    {
        return app(static::class);
    }
}
