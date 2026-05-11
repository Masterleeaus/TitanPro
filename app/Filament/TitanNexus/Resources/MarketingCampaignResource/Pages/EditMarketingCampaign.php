<?php

namespace App\Filament\TitanNexus\Resources\MarketingCampaignResource\Pages;

use App\Filament\TitanNexus\Resources\MarketingCampaignResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMarketingCampaign extends EditRecord
{
    protected static string $resource = MarketingCampaignResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
