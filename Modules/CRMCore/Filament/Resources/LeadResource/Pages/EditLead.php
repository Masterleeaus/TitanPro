<?php

namespace Modules\CRMCore\Filament\Resources\LeadResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Modules\CRMCore\Actions\Lead\UpdateLeadAction;
use Modules\CRMCore\Filament\Resources\LeadResource;
use Modules\CRMCore\Models\Lead;

class EditLead extends EditRecord
{
    protected static string $resource = LeadResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function handleRecordUpdate(\Illuminate\Database\Eloquent\Model $record, array $data): Lead
    {
        /** @var Lead $record */
        return app(UpdateLeadAction::class)->handle($record, $data);
    }
}
