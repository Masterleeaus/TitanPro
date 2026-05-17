<?php

declare(strict_types=1);

namespace Modules\Dispatch\Filament\Resources\DispatchChecklistResource\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Modules\Dispatch\Filament\Resources\DispatchChecklistResource;

class ListDispatchChecklists extends ListRecords
{
    protected static string $resource = DispatchChecklistResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
