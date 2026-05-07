<?php

namespace Modules\NexusGrowth\Filament\Plugin;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Modules\NexusGrowth\Filament\Pages\GrowthDashboardPage;
use Modules\NexusGrowth\Filament\Pages\PipelineFunnelPage;
use Modules\NexusGrowth\Filament\Pages\ChurnPredictionPage;
use Modules\NexusGrowth\Filament\Pages\RoiReportPage;

class NexusGrowthPlugin implements Plugin
{
    public function getId(): string
    {
        return 'titannexus-native';
    }

    public function register(Panel $panel): void
    {
        $panel->pages([
            GrowthDashboardPage::class,
            PipelineFunnelPage::class,
            ChurnPredictionPage::class,
            RoiReportPage::class,
        ]);
    }

    public function boot(Panel $panel): void {}

    public static function make(): static
    {
        return app(static::class);
    }
}
