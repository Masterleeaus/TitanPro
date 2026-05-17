<?php

namespace App\Filament\TitanNexus\Pages;

use App\Filament\TitanNexus\Support\NexusPanelData;
use Filament\Pages\Page;

class ContactSegments extends Page
{
    use NexusPanelData;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-tag';

    protected static string|\UnitEnum|null $navigationGroup = 'Acquisition';

    protected static ?string $navigationLabel = 'Segments';

    protected static ?int $navigationSort = 12;

    protected static ?string $title = 'Segments';

    protected static ?string $slug = 'segments';

    protected string $view = 'filament.titan-nexus.pages.nexus-page';

    public function pageConfig(): array
    {
        return [
            'kicker' => 'Acquisition',
            'heading' => 'Segments',
            'description' => 'Use this area to manage the TitanNexus workflow records assigned to Segments.',
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
