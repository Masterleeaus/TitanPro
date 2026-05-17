<?php

namespace App\Filament\TitanNexus\Resources\LeadsChannelConfigResource\Pages;

use App\Filament\TitanNexus\Resources\LeadsChannelConfigResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLeadsChannelConfigs extends ListRecords
{
    protected static string $resource = LeadsChannelConfigResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
