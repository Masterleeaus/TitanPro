<?php

namespace App\Filament\TitanNexus\Resources\VerticalPackResource\Pages;

use App\Filament\TitanNexus\Resources\VerticalPackResource;
use Filament\Resources\Pages\CreateRecord;

class CreateVerticalPack extends CreateRecord
{
    protected static string $resource = VerticalPackResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['organization_id'] = auth()->user()?->organization_id;

        return $data;
    }
}
