<?php

namespace App\Filament\TitanSolo\Resources\JobResource\Pages;

use App\Filament\TitanSolo\Resources\JobResource;
use Filament\Resources\Pages\CreateRecord;

class CreateJob extends CreateRecord
{
    protected static string $resource = JobResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['organization_id'] = auth()->user()?->organization_id;

        return $data;
    }
}
