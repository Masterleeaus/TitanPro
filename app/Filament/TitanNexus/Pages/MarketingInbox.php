<?php

namespace App\Filament\TitanNexus\Pages;

use App\Filament\TitanNexus\Support\NexusMarketingData;
use Filament\Pages\Page;

class MarketingInbox extends Page
{
    use NexusMarketingData;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-inbox';

    protected static string|\UnitEnum|null $navigationGroup = 'Outreach Automation';

    protected static ?string $navigationLabel = 'Marketing Inbox';

    protected static ?int $navigationSort = 22;

    protected static ?string $title = 'Marketing Inbox';

    protected string $view = 'filament.titan-nexus.pages.marketingbot-page';

    public function pageConfig(): array
    {
        return [
            'kicker' => 'Replies and conversations',
            'heading' => 'Review conversations from outreach channels.',
            'description' => 'Central place to triage campaign replies, objections, booking requests and handoffs.',
            'table' => 'ext_marketing_conversations',
            'columns' => [
                'id',
                'contact_id',
                'channel',
                'status',
                'last_message_at',
                'created_at',
            ],
        ];
    }
}
