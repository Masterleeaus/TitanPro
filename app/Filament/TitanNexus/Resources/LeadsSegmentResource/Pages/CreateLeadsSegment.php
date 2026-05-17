<?php

namespace App\Filament\TitanNexus\Resources\LeadsSegmentResource\Pages;

use App\Filament\TitanNexus\Resources\LeadsSegmentResource;
use Filament\Resources\Pages\CreateRecord;

class CreateLeadsSegment extends CreateRecord
{
    protected static string $resource = LeadsSegmentResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();

        return $data;
    }
}
