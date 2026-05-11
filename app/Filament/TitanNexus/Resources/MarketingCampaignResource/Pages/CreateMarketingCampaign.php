<?php

namespace App\Filament\TitanNexus\Resources\MarketingCampaignResource\Pages;

use App\Filament\TitanNexus\Resources\MarketingCampaignResource;
use Filament\Resources\Pages\CreateRecord;

class CreateMarketingCampaign extends CreateRecord
{
    protected static string $resource = MarketingCampaignResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['organization_id'] = auth()->user()?->organization_id;

        return $data;
    }
}
