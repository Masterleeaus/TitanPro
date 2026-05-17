<?php

namespace App\Filament\TitanNexus\Pages;

use App\Filament\TitanNexus\Support\NexusPanelData;
use Filament\Pages\Page;

class FollowUpQueue extends Page
{
    use NexusPanelData;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-arrow-path';

    protected static string|\UnitEnum|null $navigationGroup = 'Outreach';

    protected static ?string $navigationLabel = 'Follow-up Queue';

    protected static ?int $navigationSort = 21;

    protected static ?string $title = 'Follow-up Queue';

    protected static ?string $slug = 'follow-up-queue';

    protected string $view = 'filament.titan-nexus.pages.nexus-page';

    public function pageConfig(): array
    {
        return [
            'kicker' => static::$navigationGroup,
            'heading' => static::$title,
            'description' => 'Follow up every lead until it is booked, closed, or removed.',
            'table' => 'ext_marketing_message_histories',
            'columns' => ['id','direction','channel','status','message','created_at'],
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
