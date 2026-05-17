<?php

namespace App\Filament\Admin\Resources\PlatformCommunicatorLogResource\Pages;

use App\Filament\Admin\Resources\PlatformCommunicatorLogResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPlatformCommunications extends ListRecords
{
    protected static string $resource = PlatformCommunicatorLogResource::class;
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
