<?php

namespace App\Filament\TitanNexus\Resources\LeadPipelineEntryResource\Pages;

use App\Filament\TitanNexus\Resources\LeadPipelineEntryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateLeadPipelineEntry extends CreateRecord
{
    protected static string $resource = LeadPipelineEntryResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['organization_id'] = auth()->user()?->organization_id;

        return $data;
    }
}
