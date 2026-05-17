<?php

namespace App\Filament\TitanNexus\Pages;

use App\Filament\TitanNexus\Support\NexusMarketingData;
use Filament\Pages\Page;

class OutreachCampaigns extends Page
{
    use NexusMarketingData;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-megaphone';

    protected static string|\UnitEnum|null $navigationGroup = 'Outreach Automation';

    protected static ?string $navigationLabel = 'Campaigns';

    protected static ?int $navigationSort = 20;

    protected static ?string $title = 'Outreach Campaigns';

    protected string $view = 'filament.titan-nexus.pages.marketingbot-page';

    public function pageConfig(): array
    {
        return [
            'kicker' => 'Offers and campaigns',
            'heading' => 'Send vertical-specific offers and nurture sequences.',
            'description' => 'Campaign records from MarketingBot become TitanNexus outreach runs for WhatsApp, Telegram, email or assisted/manual channels.',
            'table' => 'ext_marketing_campaigns',
            'columns' => [
                'id',
                'name',
                'title',
                'channel',
                'status',
                'type',
                'created_at',
            ],
            'actions' => [
                [
                    'label' => 'Follow-ups',
                    'url' => '/titannexus/follow-up-sequences',
                ],
                [
                    'label' => 'Inbox',
                    'url' => '/titannexus/marketing-inbox',
                ],
            ],
        ];
    }
}
