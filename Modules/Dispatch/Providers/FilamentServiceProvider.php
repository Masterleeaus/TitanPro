<?php

declare(strict_types=1);

namespace Modules\Dispatch\Providers;

use Filament\Panel;
use Illuminate\Support\ServiceProvider;
use Modules\Dispatch\Filament\Pages\CleaningTodayBoardPage;
use Modules\Dispatch\Filament\Pages\DispatchPanelPage;
use Modules\Dispatch\Filament\Pages\DragDropSchedulerPage;
use Modules\Dispatch\Filament\Pages\RouteMapPage;
use Modules\Dispatch\Filament\Resources\CustomerLocationResource;
use Modules\Dispatch\Filament\Resources\DispatchAppointmentResource;
use Modules\Dispatch\Filament\Resources\DispatchChecklistResource;
use Modules\Dispatch\Filament\Resources\DispatchExceptionResource;
use Modules\Dispatch\Filament\Resources\DispatchRouteResource;
use Modules\Dispatch\Filament\Resources\DispatchRouteStopResource;
use Modules\Dispatch\Filament\Resources\DispatchStatusLogResource;
use Modules\Dispatch\Filament\Resources\DispatchWorkOrderResource;
use Modules\Dispatch\Filament\Resources\ServiceZoneResource;
use Modules\Dispatch\Filament\Resources\ShiftResource;
use Modules\Dispatch\Filament\Resources\TechnicianProfileResource;
use Modules\Dispatch\Filament\Resources\TechnicianSkillResource;
use Modules\Dispatch\Filament\Widgets\DispatchStatsOverview;
use Modules\Dispatch\Filament\Widgets\TechnicianCapacityWidget;

class FilamentServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if (! class_exists(Panel::class)) {
            return;
        }

        $panelId = config('dispatch.filament_panel', 'groundzero');

        $this->callAfterResolving('filament', function () use ($panelId): void {
            try {
                $panel = \Filament\Facades\Filament::getPanel($panelId);
            } catch (\Throwable) {
                return;
            }

            $panel->resources([
                ShiftResource::class,
                TechnicianProfileResource::class,
                TechnicianSkillResource::class,
                ServiceZoneResource::class,
                CustomerLocationResource::class,
                DispatchRouteResource::class,
                DispatchRouteStopResource::class,
                DispatchStatusLogResource::class,
                DispatchWorkOrderResource::class,
                DispatchAppointmentResource::class,
                DispatchChecklistResource::class,
                DispatchExceptionResource::class,
            ]);

            $panel->pages([
                DispatchPanelPage::class,
                DragDropSchedulerPage::class,
                RouteMapPage::class,
                CleaningTodayBoardPage::class,
            ]);

            $panel->widgets([
                DispatchStatsOverview::class,
                TechnicianCapacityWidget::class,
            ]);
        });
    }
}
