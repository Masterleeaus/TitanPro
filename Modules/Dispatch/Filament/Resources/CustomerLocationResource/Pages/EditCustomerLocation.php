<?php

declare(strict_types=1);

namespace Modules\Dispatch\Filament\Resources\CustomerLocationResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Modules\Dispatch\Filament\Resources\CustomerLocationResource;

class EditCustomerLocation extends EditRecord
{
    protected static string $resource = CustomerLocationResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
