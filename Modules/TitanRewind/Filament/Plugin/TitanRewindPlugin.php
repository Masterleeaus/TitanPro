<?php

namespace Modules\TitanRewind\Filament\Plugin;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Modules\TitanRewind\Filament\Resources\RewindActionResource;
use Modules\TitanRewind\Filament\Resources\RewindCaseResource;
use Modules\TitanRewind\Filament\Resources\RewindEventResource;
use Modules\TitanRewind\Filament\Resources\RewindFixResource;

class TitanRewindPlugin implements Plugin
{
    public static function make(): static
    {
        return app(static::class);
    }

    public function getId(): string
    {
        return 'titan-rewind';
    }

    public function register(Panel $panel): void
    {
        $panel->resources([
            RewindCaseResource::class,
            RewindEventResource::class,
            RewindFixResource::class,
            RewindActionResource::class,
        ]);
    }

    public function boot(Panel $panel): void {}
}
