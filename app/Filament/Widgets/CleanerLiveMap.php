<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class CleanerLiveMap extends Widget
{
    protected string $view = 'filament.widgets.cleaner-live-map';

    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 2;
}
