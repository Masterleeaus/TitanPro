<?php

namespace App\Filament\TitanNexus\Resources\LeadsCampaignResource\Pages;

use App\Filament\TitanNexus\Resources\LeadsCampaignResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewLeadsCampaign extends ViewRecord
{
    protected static string $resource = LeadsCampaignResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
