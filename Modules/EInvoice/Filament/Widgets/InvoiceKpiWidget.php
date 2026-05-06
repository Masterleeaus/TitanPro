<?php

namespace Modules\EInvoice\Filament\Widgets;

class InvoiceKpiWidget
{
    public static function metrics(): array
    {
        return \Modules\EInvoice\UI\ControlPanel\ControlPanelLayout::metrics();
    }
}
