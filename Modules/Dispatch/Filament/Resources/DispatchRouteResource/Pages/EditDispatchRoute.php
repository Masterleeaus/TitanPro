<?php

declare(strict_types=1);

namespace Modules\Dispatch\Filament\Resources\DispatchRouteResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Modules\Dispatch\Filament\Resources\DispatchRouteResource;

class EditDispatchRoute extends EditRecord
{
    protected static string $resource = DispatchRouteResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
