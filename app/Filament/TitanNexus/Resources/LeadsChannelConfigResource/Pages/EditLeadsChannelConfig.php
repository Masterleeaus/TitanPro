<?php

namespace App\Filament\TitanNexus\Resources\LeadsChannelConfigResource\Pages;

use App\Filament\TitanNexus\Resources\LeadsChannelConfigResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLeadsChannelConfig extends EditRecord
{
    protected static string $resource = LeadsChannelConfigResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
