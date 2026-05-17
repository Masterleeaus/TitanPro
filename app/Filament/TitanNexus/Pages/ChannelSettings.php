<?php

namespace App\Filament\TitanNexus\Pages;

use App\Filament\TitanNexus\Support\NexusPanelData;
use Filament\Pages\Page;

class ChannelSettings extends Page
{
    use NexusPanelData;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-wifi';

    protected static string|\UnitEnum|null $navigationGroup = 'Channels';

    protected static ?string $navigationLabel = 'Channel Settings';

    protected static ?int $navigationSort = 50;

    protected static ?string $title = 'Channel Settings';

    protected static ?string $slug = 'channel-settings';

    protected string $view = 'filament.titan-nexus.pages.nexus-page';

    public function pageConfig(): array
    {
        return [
            'kicker' => static::$navigationGroup,
            'heading' => static::$title,
            'description' => 'Check the communication channels used by TitanNexus outreach.',
            'table' => 'ext_whatsapp_channels',
            'columns' => ['id','name','phone_number','status','created_at'],
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
