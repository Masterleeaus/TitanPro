<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class PwaLaunchWidget extends Widget
{
    protected string $view = 'filament.widgets.pwa-launch-widget';

    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = 'full';
}
