<?php

namespace App\Filament\TitanNexus\Resources\ContractDocumentResource\Pages;

use App\Filament\TitanNexus\Resources\ContractDocumentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListContractDocument extends ListRecords
{
    protected static string $resource = ContractDocumentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
