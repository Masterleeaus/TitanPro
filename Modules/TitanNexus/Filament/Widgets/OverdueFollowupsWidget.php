<?php

namespace Modules\TitanNexus\Filament\Widgets;

use Filament\Widgets\Widget;

/** Overdue follow-ups AI widget. */
class OverdueFollowupsWidget extends Widget
{
    protected static ?string $heading = 'Overdue Follow-ups';

    protected static ?int $sort = 21;

    protected string $view = 'titannexus::filament.widgets.simple-widget';

    protected function getViewData(): array
    {
        return [
            'title' => 'Overdue Follow-ups',
            'description' => 'Overdue follow-ups AI widget is enabled.',
            'status' => 'Ready',
        ];
    }

    public function handle(): array
    {
        return ['enabled' => true, 'purpose' => 'Overdue follow-ups AI widget.'];
    }
}
