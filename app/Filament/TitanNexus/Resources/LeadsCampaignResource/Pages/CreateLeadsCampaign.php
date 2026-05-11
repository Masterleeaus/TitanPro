<?php

namespace App\Filament\TitanNexus\Resources\LeadsCampaignResource\Pages;

use App\Filament\TitanNexus\Resources\LeadsCampaignResource;
use Filament\Resources\Pages\CreateRecord;

class CreateLeadsCampaign extends CreateRecord
{
    protected static string $resource = LeadsCampaignResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();

        return $data;
    }
}
