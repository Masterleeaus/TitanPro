<?php

namespace Modules\CRMCore\Filament\Resources\LeadScoringResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\CRMCore\Filament\Resources\LeadScoringResource;

class CreateLeadScoring extends CreateRecord
{
    protected static string $resource = LeadScoringResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['lead_status_id'] ??= LeadScoringResource::defaultLeadStatusId();
        $data['lead_source_id'] ??= LeadScoringResource::defaultLeadSourceId();
        $data['created_by_id'] ??= auth()->id();
        $data['updated_by_id'] ??= auth()->id();

        return $data;
    }
}
