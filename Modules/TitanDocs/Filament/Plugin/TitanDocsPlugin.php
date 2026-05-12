<?php

namespace Modules\TitanDocs\Filament\Plugin;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Modules\TitanDocs\Filament\Pages\TitanDocsControlPanel;

class TitanDocsPlugin implements Plugin
{
    public static function make(): static
    {
        return app(static::class);
    }

    public function getId(): string
    {
        return 'titandocs';
    }

    public function register(Panel $panel): void
    {
        $panel->pages([
            TitanDocsControlPanel::class,
        ]);
    }

    public function boot(Panel $panel): void {}
}
