<?php

namespace App\Filament\TitanNexus\Resources\LeadsChannelConfigResource\Pages;

use App\Filament\TitanNexus\Resources\LeadsChannelConfigResource;
use Filament\Resources\Pages\CreateRecord;

class CreateLeadsChannelConfig extends CreateRecord
{
    protected static string $resource = LeadsChannelConfigResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();

        return $data;
    }
}
