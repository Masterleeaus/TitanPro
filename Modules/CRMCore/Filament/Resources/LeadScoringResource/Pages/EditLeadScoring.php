<?php

namespace Modules\CRMCore\Filament\Resources\LeadScoringResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Modules\CRMCore\Filament\Resources\LeadScoringResource;

class EditLeadScoring extends EditRecord
{
    protected static string $resource = LeadScoringResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['updated_by_id'] ??= auth()->id();

        return $data;
    }
}
