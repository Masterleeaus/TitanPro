<?php

namespace App\Filament\Resources\JobChecklistItemResource\Pages;

use App\Filament\Resources\JobChecklistItemResource;
use Filament\Resources\Pages\CreateRecord;

class CreateJobChecklistItem extends CreateRecord
{
    protected static string $resource = JobChecklistItemResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['organization_id'] = auth()->user()?->organization_id;

        return $data;
    }
}
