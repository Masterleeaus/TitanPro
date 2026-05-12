<?php

namespace Modules\TitanSoloDash\Filament\Plugin;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Modules\TitanSoloDash\Filament\Pages\SoloDailyPlannerPage;
use Modules\TitanSoloDash\Filament\Pages\QuickCreatePage;
use Modules\TitanSoloDash\Filament\Pages\SoloKpiPage;
use Modules\TitanSoloDash\Filament\Pages\SoloSettingsPage;

class TitanSoloDashPlugin implements Plugin
{
    public function getId(): string
    {
        return 'titansolo-native';
    }

    public function register(Panel $panel): void
    {
        $panel->pages([
            SoloDailyPlannerPage::class,
            QuickCreatePage::class,
            SoloKpiPage::class,
            SoloSettingsPage::class,
        ]);
    }

    public function boot(Panel $panel): void {}

    public static function make(): static
    {
        return app(static::class);
    }
}
