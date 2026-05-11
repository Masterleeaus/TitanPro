<?php

namespace App\Filament\GroundZero\Resources\WorkJobResource\Pages;

use App\Filament\GroundZero\Resources\WorkJobResource;
use Filament\Resources\Pages\CreateRecord;

class CreateWorkJob extends CreateRecord
{
    protected static string $resource = WorkJobResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = auth()->user();
        $data['company_id'] = $user?->organization_id ?? 0;
        $data['user_id']    = $user?->id ?? 0;
        return $data;
    }
}
