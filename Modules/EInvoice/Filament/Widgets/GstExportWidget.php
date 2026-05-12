<?php

namespace Modules\EInvoice\Filament\Widgets;

use Filament\Widgets\Widget;
use Modules\EInvoice\Entities\Invoice;

/**
 * Widget that shows GST/tax export summary and a download link for the
 * current tenant's invoices. All inline logic defers to Action classes.
 */
class GstExportWidget extends Widget
{
    protected static string $view = 'einvoice::filament.widgets.gst-export';

    protected int | string | array $columnSpan = 'full';

    public function getViewData(): array
    {
        $invoices = Invoice::query()
            ->whereIn('status', ['paid'])
            ->selectRaw('currency, COUNT(*) as count, SUM(tax_total) as total_gst')
            ->groupBy('currency')
            ->get();

        return [
            'summary'    => $invoices,
            'exportUrl'  => '#',
        ];
    }

    public static function canView(): bool
    {
        return (bool) auth()->user()?->can('einvoice.export');
    }
}
