<?php

namespace App\Filament\TitanNexus\Pages;

use App\Filament\TitanNexus\Support\NexusPanelData;
use Filament\Pages\Page;

class OutreachRuns extends Page
{
    use NexusPanelData;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-megaphone';

    protected static string|\UnitEnum|null $navigationGroup = 'Outreach';

    protected static ?string $navigationLabel = 'Outreach Runs';

    protected static ?int $navigationSort = 20;

    protected static ?string $title = 'Outreach Runs';

    protected static ?string $slug = 'outreach-runs';

    protected string $view = 'filament.titan-nexus.pages.nexus-page';

    public function pageConfig(): array
    {
        return [
            'kicker' => static::$navigationGroup,
            'heading' => static::$title,
            'description' => 'Send offers and coordinate channel activity for each acquisition workflow.',
            'table' => 'ext_marketing_campaigns',
            'columns' => ['id','name','title','channel','status','type','created_at'],
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
