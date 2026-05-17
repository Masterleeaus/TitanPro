<?php

namespace Modules\EInvoice\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\EInvoice\Filament\Pages\AiNotesPage;
use Modules\EInvoice\Filament\Resources\InvoiceResource;
use Modules\EInvoice\Filament\Widgets\GstExportWidget;
use Modules\EInvoice\Filament\Widgets\ZeroPayHandoffWidget;

/**
 * Registers EInvoice Filament panel components (resources, pages, widgets)
 * into the host application's Filament panel.
 */
class FilamentServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Intentionally empty: resources are registered in boot().
    }

    public function boot(): void
    {
        // Register into Filament if the panel manager is available.
        if (! class_exists(\Filament\Panel::class)) {
            return;
        }

        $this->callAfterResolving(\Filament\PanelRegistry::class, function (\Filament\PanelRegistry $registry): void {
            foreach ($registry->all() as $panel) {
                $panel->resources([InvoiceResource::class]);
                $panel->pages([AiNotesPage::class]);
                $panel->widgets([GstExportWidget::class, ZeroPayHandoffWidget::class]);
            }
        });
    }
}
