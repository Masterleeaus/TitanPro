<?php

namespace Modules\Biometric\Filament\Plugin;

use Filament\Contracts\Plugin;
use Filament\Panel;

class BiometricPlugin implements Plugin
{
    public function getId(): string
    {
        return 'biometric';
    }

    public function register(Panel $panel): void
    {
        // Biometric currently renders through module web routes under /account.
    }

    public function boot(Panel $panel): void {}

    public static function make(): static
    {
        return app(static::class);
    }
}
