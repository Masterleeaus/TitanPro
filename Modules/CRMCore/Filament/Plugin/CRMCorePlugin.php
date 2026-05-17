<?php

namespace Modules\CRMCore\Filament\Plugin;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Modules\CRMCore\Filament\Pages\CRMCoreOverview;
use Modules\CRMCore\Filament\Resources\ClientPipelineResource;
use Modules\CRMCore\Filament\Resources\CRMCoreActivityLogResource;
use Modules\CRMCore\Filament\Resources\DealProjectResource;
use Modules\CRMCore\Filament\Resources\DealResource;
use Modules\CRMCore\Filament\Resources\LeadResource;
use Modules\CRMCore\Filament\Resources\LeadScoringResource;
use Modules\CRMCore\Filament\Widgets\PipelineOverviewWidget;

class CRMCorePlugin implements Plugin
{
    public function getId(): string
    {
        return 'crmcore';
    }

    public function register(Panel $panel): void
    {
        $panel
            ->resources([
                LeadResource::class,
                LeadScoringResource::class,
                DealResource::class,
                DealProjectResource::class,
                ClientPipelineResource::class,
                CRMCoreActivityLogResource::class,
            ])
            ->pages([
                CRMCoreOverview::class,
            ])
            ->widgets([
                PipelineOverviewWidget::class,
            ]);
    }

    public function boot(Panel $panel): void
    {
        //
    }

    public static function make(): static
    {
        return app(static::class);
    }
}

