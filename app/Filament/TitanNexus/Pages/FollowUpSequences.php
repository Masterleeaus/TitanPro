<?php

namespace App\Filament\TitanNexus\Pages;

use App\Filament\TitanNexus\Support\NexusMarketingData;
use Filament\Pages\Page;

class FollowUpSequences extends Page
{
    use NexusMarketingData;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-arrow-path';

    protected static string|\UnitEnum|null $navigationGroup = 'Outreach Automation';

    protected static ?string $navigationLabel = 'Follow-ups';

    protected static ?int $navigationSort = 21;

    protected static ?string $title = 'Follow-up Sequences';

    protected string $view = 'filament.titan-nexus.pages.marketingbot-page';

    public function pageConfig(): array
    {
        return [
            'kicker' => 'Nurture automation',
            'heading' => 'Follow up leads until they reply, book, or decline.',
            'description' => 'Monitor message histories and campaign responses for automated or manual follow-up actions.',
            'table' => 'ext_marketing_message_histories',
            'columns' => [
                'id',
                'direction',
                'channel',
                'status',
                'message',
                'created_at',
            ],
        ];
    }
}
