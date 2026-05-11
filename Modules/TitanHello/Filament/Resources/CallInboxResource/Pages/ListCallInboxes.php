<?php

namespace Modules\TitanHello\Filament\Resources\CallInboxResource\Pages;

use Filament\Resources\Pages\ListRecords;
use Modules\TitanHello\Filament\Resources\CallInboxResource;

class ListCallInboxes extends ListRecords
{
    protected static string $resource = CallInboxResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function getListeners(): array
    {
        return array_merge(parent::getListeners(), [
            'echo:titanhello.calls,call.status.updated' => '$refresh',
        ]);
    }
}
