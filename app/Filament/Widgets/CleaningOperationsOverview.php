<?php

namespace App\Filament\Widgets;

use App\Support\CleaningAdminMetrics;
use Filament\Widgets\Widget;

class CleaningOperationsOverview extends Widget
{
    protected string $view = 'filament.widgets.cleaning-operations-overview';

    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 1;
}
