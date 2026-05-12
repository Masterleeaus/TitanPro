<?php

namespace App\Filament\TitanNexus\Resources\LeadsInboxResource\Pages;

use App\Filament\TitanNexus\Resources\LeadsInboxResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLeadsInbox extends EditRecord
{
    protected static string $resource = LeadsInboxResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
