<?php

namespace App\Filament\TitanGo\Widgets;

use App\Support\TitanGo\PwaFeatureCatalog;
use Filament\Widgets\Widget;

class PwaFeatureMatrixWidget extends Widget
{
    protected string $view = 'filament.titango.widgets.pwa-feature-matrix-widget';

    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 7;

    protected function getViewData(): array
    {
        return app(PwaFeatureCatalog::class)->summary();
    }
}
