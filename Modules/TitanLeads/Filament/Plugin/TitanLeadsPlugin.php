<?php

namespace Modules\TitanLeads\Filament\Plugin;

use Filament\Contracts\Plugin;
use Filament\Panel;

class TitanLeadsPlugin implements Plugin
{
    public static function make(): static
    {
        return app(static::class);
    }

    public function getId(): string
    {
        return 'titan-leads';
    }

    public function register(Panel $panel): void {}

    public function boot(Panel $panel): void {}
}
