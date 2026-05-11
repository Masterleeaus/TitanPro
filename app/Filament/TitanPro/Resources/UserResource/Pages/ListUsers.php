<?php

namespace App\Filament\TitanPro\Resources\UserResource\Pages;

use App\Filament\TitanPro\Resources\UserResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
