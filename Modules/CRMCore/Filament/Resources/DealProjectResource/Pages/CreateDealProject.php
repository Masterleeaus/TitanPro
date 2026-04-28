<?php

namespace Modules\CRMCore\Filament\Resources\DealProjectResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\CRMCore\Filament\Resources\DealProjectResource;

class CreateDealProject extends CreateRecord
{
    protected static string $resource = DealProjectResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['pipeline_id'] ??= DealProjectResource::defaultPipelineId();
        $data['deal_stage_id'] ??= DealProjectResource::defaultStageId();
        $data['currency'] ??= 'USD';
        $data['created_by_id'] ??= auth()->id();
        $data['updated_by_id'] ??= auth()->id();

        return $data;
    }
}
