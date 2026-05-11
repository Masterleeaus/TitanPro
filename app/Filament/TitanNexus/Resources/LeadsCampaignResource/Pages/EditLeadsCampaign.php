<?php

namespace App\Filament\TitanNexus\Resources\LeadsCampaignResource\Pages;

use App\Filament\TitanNexus\Resources\LeadsCampaignResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLeadsCampaign extends EditRecord
{
    protected static string $resource = LeadsCampaignResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
