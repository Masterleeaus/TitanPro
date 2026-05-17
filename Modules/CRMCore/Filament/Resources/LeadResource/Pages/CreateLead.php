<?php

namespace Modules\CRMCore\Filament\Resources\LeadResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\CRMCore\Actions\Lead\CreateLeadAction;
use Modules\CRMCore\Filament\Resources\LeadResource;
use Modules\CRMCore\Models\Lead;

class CreateLead extends CreateRecord
{
    protected static string $resource = LeadResource::class;

    protected function handleRecordCreation(array $data): Lead
    {
        return app(CreateLeadAction::class)->handle($data);
    }
}
