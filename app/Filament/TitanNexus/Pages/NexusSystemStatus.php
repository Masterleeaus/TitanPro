<?php

namespace App\Filament\TitanNexus\Pages;

use App\Filament\TitanNexus\Support\NexusPanelData;
use Filament\Pages\Page;

class NexusSystemStatus extends Page
{
    use NexusPanelData;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-shield-check';

    protected static string|\UnitEnum|null $navigationGroup = 'TitanNexus';

    protected static ?string $navigationLabel = 'System Status';

    protected static ?int $navigationSort = 99;

    protected static ?string $title = 'TitanNexus System Status';

    protected static ?string $slug = 'system-status';

    protected string $view = 'filament.titan-nexus.pages.nexus-page';

    public function pageConfig(): array
    {
        return [
            'kicker' => 'TitanNexus',
            'heading' => 'TitanNexus System Status',
            'description' => 'Use this area to manage the TitanNexus workflow records assigned to System Status.',
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
