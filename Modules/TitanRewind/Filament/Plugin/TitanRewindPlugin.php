<?php

namespace Modules\TitanRewind\Filament\Plugin;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Modules\TitanRewind\Filament\Pages\AiCorrectionPage;
use Modules\TitanRewind\Filament\Pages\AuditTrailPage;
use Modules\TitanRewind\Filament\Pages\RewindRequestPage;
use Modules\TitanRewind\Filament\Pages\SnapshotComparisonPage;
use Modules\TitanRewind\Filament\Resources\RewindActionResource;
use Modules\TitanRewind\Filament\Resources\RewindCaseResource;
use Modules\TitanRewind\Filament\Resources\RewindEventResource;
use Modules\TitanRewind\Filament\Resources\RewindFixResource;
use Modules\TitanRewind\Filament\Resources\RewindRequestResource;

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
            RewindRequestResource::class,
        ]);

        $panel->pages([
            AuditTrailPage::class,
            RewindRequestPage::class,
            SnapshotComparisonPage::class,
            AiCorrectionPage::class,
        ]);
    }

    public function boot(Panel $panel): void {}
}
