<?php

namespace Modules\EInvoice\Filament\Plugin;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Modules\EInvoice\Filament\Pages\AiNotesPage;
use Modules\EInvoice\Filament\Pages\InvoiceControlPanel;
use Modules\EInvoice\Filament\Resources\InvoiceResource;
use Modules\EInvoice\Filament\Widgets\GstExportWidget;
use Modules\EInvoice\Filament\Widgets\InvoiceKpiWidget;
use Modules\EInvoice\Filament\Widgets\ZeroPayHandoffWidget;

class EInvoicePlugin implements Plugin
{
    public static function make(): static
    {
        return app(static::class);
    }

    public function getId(): string
    {
        return 'einvoice';
    }

    public function register(Panel $panel): void
    {
        $panel
            ->resources([
                InvoiceResource::class,
            ])
            ->pages([
                InvoiceControlPanel::class,
                AiNotesPage::class,
            ])
            ->widgets([
                InvoiceKpiWidget::class,
                GstExportWidget::class,
                ZeroPayHandoffWidget::class,
            ]);
    }

    public function boot(Panel $panel): void {}
}
