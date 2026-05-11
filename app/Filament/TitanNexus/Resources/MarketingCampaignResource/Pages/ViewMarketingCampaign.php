<?php

namespace App\Filament\TitanNexus\Resources\MarketingCampaignResource\Pages;

use App\Filament\TitanNexus\Resources\MarketingCampaignResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewMarketingCampaign extends ViewRecord
{
    protected static string $resource = MarketingCampaignResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
