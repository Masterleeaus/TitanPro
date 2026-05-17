<?php

namespace App\Filament\TitanNexus\Pages;

use App\Filament\TitanNexus\Support\NexusPanelData;
use Filament\Pages\Page;

class NexusContacts extends Page
{
    use NexusPanelData;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-users';

    protected static string|\UnitEnum|null $navigationGroup = 'Acquisition';

    protected static ?string $navigationLabel = 'Contacts';

    protected static ?int $navigationSort = 11;

    protected static ?string $title = 'Contacts';

    protected static ?string $slug = 'contacts';

    protected string $view = 'filament.titan-nexus.pages.nexus-page';

    public function pageConfig(): array
    {
        return [
            'kicker' => static::$navigationGroup,
            'heading' => static::$title,
            'description' => 'Manage people and organizations used by TitanNexus workflows.',
            'table' => 'ext_contacts',
            'columns' => ['id','name','company','email','phone','status','created_at'],
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
