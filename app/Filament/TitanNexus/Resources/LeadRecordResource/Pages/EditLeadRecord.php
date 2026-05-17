<?php

namespace App\Filament\TitanNexus\Resources\LeadRecordResource\Pages;

use App\Filament\TitanNexus\Resources\LeadRecordResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditLeadRecord extends EditRecord
{
    protected static string $resource = LeadRecordResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
