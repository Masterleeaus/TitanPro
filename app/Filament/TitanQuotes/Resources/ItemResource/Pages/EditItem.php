<?php

namespace App\Filament\TitanQuotes\Resources\ItemResource\Pages;

use App\Filament\TitanQuotes\Resources\ItemResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditItem extends EditRecord
{
    protected static string $resource = ItemResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }
}
