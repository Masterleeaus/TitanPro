<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class InvoiceVisibilityWidget extends Widget
{
    protected string $view = 'filament.widgets.invoice-visibility-widget';

    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 4;
}
