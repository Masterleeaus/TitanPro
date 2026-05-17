<?php

namespace Modules\EInvoice\Filament\Widgets;

use Filament\Widgets\Widget;
use Modules\EInvoice\Entities\Invoice;

/**
 * Widget that surfaces invoices ready for ZeroPay handoff (sent + unpaid).
 * Triggers the handoff via PrepareZeroPayHandoffAction — no business logic inline.
 */
class ZeroPayHandoffWidget extends Widget
{
    protected static string $view = 'einvoice::filament.widgets.zeropay-handoff';

    protected int | string | array $columnSpan = 'full';

    public function getViewData(): array
    {
        $pending = Invoice::query()
            ->where('status', 'sent')
            ->orderBy('due_date')
            ->limit(20)
            ->get();

        return [
            'pending'    => $pending,
            'handoffUrl' => '#',
        ];
    }

    public static function canView(): bool
    {
        $user = auth()->user();

        return (bool) (
            $user?->can('einvoice.view')
            || $user?->can('money.view')
        );
    }
}
