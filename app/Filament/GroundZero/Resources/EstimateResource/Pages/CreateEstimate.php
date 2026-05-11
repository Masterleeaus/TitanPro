<?php

namespace App\Filament\GroundZero\Resources\EstimateResource\Pages;

use App\Filament\GroundZero\Resources\EstimateResource;
use Filament\Resources\Pages\CreateRecord;

class CreateEstimate extends CreateRecord
{
    protected static string $resource = EstimateResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['organization_id'] = auth()->user()?->organization_id;

        return $data;
    }
}
