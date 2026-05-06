<?php

namespace Modules\TitanNexus\Filament\Widgets;

use Filament\Widgets\Widget;

class LeadQualityWidget extends Widget
{
    protected static ?string $heading = 'Lead Quality';

    protected static ?int $sort = 22;

    protected string $view = 'titannexus::filament.widgets.simple-widget';

    protected function getViewData(): array
    {
        return [
            'title' => 'Lead Quality',
            'description' => 'Lead quality widget is enabled.',
            'status' => 'Ready',
        ];
    }

    public function card(): string
    {
        return 'lead_quality';
    }
}
