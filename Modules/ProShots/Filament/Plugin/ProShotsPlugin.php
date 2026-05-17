<?php

namespace Modules\ProShots\Filament\Plugin;

use Filament\Contracts\Plugin;
use Filament\Panel;

class ProShotsPlugin implements Plugin
{
    public static function make(): static
    {
        return app(static::class);
    }

    public function getId(): string
    {
        return 'proshots';
    }

    public function register(Panel $panel): void {}

    public function boot(Panel $panel): void {}
}
