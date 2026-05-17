<?php

namespace App\Filament\TitanNexus\Resources\OutreachRunResource\Pages;

use App\Filament\TitanNexus\Resources\OutreachRunResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListOutreachRun extends ListRecords
{
    protected static string $resource = OutreachRunResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
