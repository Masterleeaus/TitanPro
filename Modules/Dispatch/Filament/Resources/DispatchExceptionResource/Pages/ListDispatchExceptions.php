<?php

declare(strict_types=1);

namespace Modules\Dispatch\Filament\Resources\DispatchExceptionResource\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Modules\Dispatch\Filament\Resources\DispatchExceptionResource;

class ListDispatchExceptions extends ListRecords
{
    protected static string $resource = DispatchExceptionResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
