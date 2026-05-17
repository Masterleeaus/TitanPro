<?php

namespace App\Filament\TitanNexus\Resources\ContractDocumentResource\Pages;

use App\Filament\TitanNexus\Resources\ContractDocumentResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditContractDocument extends EditRecord
{
    protected static string $resource = ContractDocumentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
