<?php

namespace App\Filament\TitanNexus\Pages;

use App\Filament\TitanNexus\Support\NexusPanelData;
use Filament\Pages\Page;

class LeadFinder extends Page
{
    use NexusPanelData;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-magnifying-glass';

    protected static string|\UnitEnum|null $navigationGroup = 'Acquisition';

    protected static ?string $navigationLabel = 'Lead Discovery';

    protected static ?int $navigationSort = 10;

    protected static ?string $title = 'Lead Discovery';

    protected static ?string $slug = 'lead-finder';

    protected string $view = 'filament.titan-nexus.pages.nexus-page';

    public function pageConfig(): array
    {
        return [
            'kicker' => static::$navigationGroup,
            'heading' => static::$title,
            'description' => 'Build qualified lead lists for active service verticals.',
            'table' => 'ext_contacts',
            'columns' => ['id','name','first_name','last_name','email','phone','company','status','created_at'],
            'metrics' => [],
            'instructions' => [
                ['title' => 'Open records', 'body' => 'Review the latest records in this workflow area.'],
                ['title' => 'Confirm status', 'body' => 'Update status values after each action.'],
                ['title' => 'Move forward', 'body' => 'Send ready records to the next TitanNexus workflow area.'],
            ],
            'actions' => [],
        ];
    }
}
