<?php

namespace Modules\TitanNexus\Filament\Widgets;

use Filament\Widgets\Widget;

/** Campaign performance AI widget. */
class CampaignPerformanceWidget extends Widget
{
    protected static ?string $heading = 'Campaign Performance';

    protected static ?int $sort = 20;

    protected string $view = 'titannexus::filament.widgets.simple-widget';

    protected function getViewData(): array
    {
        return [
            'title' => 'Campaign Performance',
            'description' => 'Campaign performance AI widget is enabled.',
            'status' => 'Ready',
        ];
    }

    public function handle(): array
    {
        return ['enabled' => true, 'purpose' => 'Campaign performance AI widget.'];
    }
}
