<?php

namespace App\Filament\TitanNexus\Pages;

use App\Filament\TitanNexus\Support\NexusPanelData;
use Filament\Pages\Page;

class ContractsPaperwork extends Page
{
    use NexusPanelData;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-document-text';

    protected static string|\UnitEnum|null $navigationGroup = 'Delivery Readiness';

    protected static ?string $navigationLabel = 'Contracts & Paperwork';

    protected static ?int $navigationSort = 41;

    protected static ?string $title = 'Contracts & Paperwork';

    protected static ?string $slug = 'contracts-paperwork';

    protected string $view = 'filament.titan-nexus.pages.nexus-page';

    public function pageConfig(): array
    {
        return [
            'kicker' => static::$navigationGroup,
            'heading' => static::$title,
            'description' => 'Prepare the paperwork required before work begins.',
            'table' => 'nexus_contract_documents',
            'columns' => ['id','title','vertical','document_type','status','created_at'],
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
