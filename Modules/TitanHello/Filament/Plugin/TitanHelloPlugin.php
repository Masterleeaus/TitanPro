<?php

namespace Modules\TitanHello\Filament\Plugin;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Modules\TitanHello\Filament\Resources\CallInboxResource;
use Modules\TitanHello\Filament\Resources\OutboundDialerResource;

class TitanHelloPlugin implements Plugin
{
    public static function make(): static
    {
        return new static();
    }

    public function getId(): string
    {
        return 'titanhello';
    }

    public function register(Panel $panel): void
    {
        $panel->resources([
            CallInboxResource::class,
            OutboundDialerResource::class,
        ]);
    }

    public function boot(Panel $panel): void
    {
        //
    }
}
