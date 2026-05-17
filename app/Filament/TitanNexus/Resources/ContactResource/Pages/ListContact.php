<?php

namespace App\Filament\TitanNexus\Resources\ContactResource\Pages;

use App\Filament\TitanNexus\Resources\ContactResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListContact extends ListRecords
{
    protected static string $resource = ContactResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
