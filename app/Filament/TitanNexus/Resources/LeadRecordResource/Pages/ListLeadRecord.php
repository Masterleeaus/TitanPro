<?php

namespace App\Filament\TitanNexus\Resources\LeadRecordResource\Pages;

use App\Filament\TitanNexus\Resources\LeadRecordResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLeadRecord extends ListRecords
{
    protected static string $resource = LeadRecordResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
