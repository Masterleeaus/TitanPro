<?php

namespace App\Filament\TitanNexus\Resources\LeadPipelineEntryResource\Pages;

use App\Filament\TitanNexus\Resources\LeadPipelineEntryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLeadPipelineEntry extends EditRecord
{
    protected static string $resource = LeadPipelineEntryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
