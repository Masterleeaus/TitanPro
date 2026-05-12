<?php

namespace Modules\TitanStudioHub\Filament\Plugin;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Modules\TitanStudioHub\Filament\Pages\ContentCalendarPage;
use Modules\TitanStudioHub\Filament\Pages\BrandLibraryPage;
use Modules\TitanStudioHub\Filament\Pages\CampaignTrackerPage;
use Modules\TitanStudioHub\Filament\Pages\CreativeBriefPage;

class TitanStudioHubPlugin implements Plugin
{
    public function getId(): string
    {
        return 'titanstudio-native';
    }

    public function register(Panel $panel): void
    {
        $panel->pages([
            ContentCalendarPage::class,
            BrandLibraryPage::class,
            CampaignTrackerPage::class,
            CreativeBriefPage::class,
        ]);
    }

    public function boot(Panel $panel): void {}

    public static function make(): static
    {
        return app(static::class);
    }
}
