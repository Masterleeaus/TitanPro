<?php

namespace App\Filament\GroundZero\Resources\JobResource\Pages;

use App\Filament\GroundZero\Resources\JobResource;
use Filament\Resources\Pages\CreateRecord;

class CreateJob extends CreateRecord
{
    protected static string $resource = JobResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data = JobResource::prepareFormData($data);
        $data['organization_id'] = auth()->user()?->organization_id;

        return $data;
    }
}
