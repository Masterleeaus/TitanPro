<?php

namespace Modules\GroundZeroOps\Filament\Plugin;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Modules\GroundZeroOps\Filament\Pages\LiveDispatchBoard;
use Modules\GroundZeroOps\Filament\Pages\TechnicianMapPage;
use Modules\GroundZeroOps\Filament\Pages\ShiftManagerPage;
use Modules\GroundZeroOps\Filament\Pages\IncidentLogPage;

class GroundZeroOpsPlugin implements Plugin
{
    public function getId(): string
    {
        return 'groundzero-native';
    }

    public function register(Panel $panel): void
    {
        $panel->pages([
            LiveDispatchBoard::class,
            TechnicianMapPage::class,
            ShiftManagerPage::class,
            IncidentLogPage::class,
        ]);
    }

    public function boot(Panel $panel): void {}

    public static function make(): static
    {
        return app(static::class);
    }
}
