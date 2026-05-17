<?php

namespace Modules\TitanHello\Filament\Resources\CallInboxResource\Pages;

use Filament\Resources\Pages\ViewRecord;
use Modules\TitanHello\Filament\Resources\CallInboxResource;

class ViewCallInbox extends ViewRecord
{
    protected static string $resource = CallInboxResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
