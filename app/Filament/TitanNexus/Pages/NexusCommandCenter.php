<?php

namespace App\Filament\TitanNexus\Pages;

use App\Filament\TitanNexus\Support\NexusPanelData;
use Filament\Pages\Page;

class NexusCommandCenter extends Page
{
    use NexusPanelData;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-command-line';

    protected static string|\UnitEnum|null $navigationGroup = 'TitanNexus';

    protected static ?string $navigationLabel = 'Command Center';

    protected static ?int $navigationSort = 1;

    protected static ?string $title = 'TitanNexus Command Center';

    protected static ?string $slug = 'command-center';

    protected string $view = 'filament.titan-nexus.pages.nexus-page';

    public function pageConfig(): array
    {
        return [
            'kicker' => 'TitanNexus',
            'heading' => 'TitanNexus Command Center',
            'description' => 'Use this area to manage the TitanNexus workflow records assigned to Command Center.',
            'table' => null,
            'columns' => [],
            'metrics' => [],
            'instructions' => [
                ['title' => 'Review records', 'body' => 'Open the related resource records from the sidebar.'],
                ['title' => 'Update status', 'body' => 'Keep each record aligned with the current workflow stage.'],
                ['title' => 'Complete next action', 'body' => 'Move ready records to the next TitanNexus workflow area.'],
            ],
            'actions' => [],
        ];
    }
}
