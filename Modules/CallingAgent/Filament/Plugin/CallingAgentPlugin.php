<?php

namespace Modules\CallingAgent\Filament\Plugin;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Modules\CallingAgent\Providers\FilamentServiceProvider;

/**
 * Filament plugin for CallingAgent.
 *
 * Register it in your panel provider:
 *
 *   $panel->plugins([
 *       \Modules\CallingAgent\Filament\Plugin\CallingAgentPlugin::make(),
 *   ])
 */
class CallingAgentPlugin implements Plugin
{
    public static function make(): static
    {
        return new static();
    }

    public function getId(): string
    {
        return 'calling-agent';
    }

    public function register(Panel $panel): void
    {
        $panel->resources(FilamentServiceProvider::resources());
    }

    public function boot(Panel $panel): void
    {
        // Nothing additional needed at boot time.
    }
}
