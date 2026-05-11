<?php

namespace App\Filament\TitanNexus\Resources\LeadsInboxResource\Pages;

use App\Filament\TitanNexus\Resources\LeadsInboxResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLeadsInbox extends ListRecords
{
    protected static string $resource = LeadsInboxResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
