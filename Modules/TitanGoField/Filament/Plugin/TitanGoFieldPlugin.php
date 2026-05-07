<?php

namespace Modules\TitanGoField\Filament\Plugin;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Modules\TitanGoField\Filament\Pages\MyJobsPage;
use Modules\TitanGoField\Filament\Pages\CheckInPage;
use Modules\TitanGoField\Filament\Pages\JobChecklistPage;
use Modules\TitanGoField\Filament\Pages\FieldPhotoLogPage;

class TitanGoFieldPlugin implements Plugin
{
    public function getId(): string
    {
        return 'titango-native';
    }

    public function register(Panel $panel): void
    {
        $panel->pages([
            MyJobsPage::class,
            CheckInPage::class,
            JobChecklistPage::class,
            FieldPhotoLogPage::class,
        ]);
    }

    public function boot(Panel $panel): void {}

    public static function make(): static
    {
        return app(static::class);
    }
}
