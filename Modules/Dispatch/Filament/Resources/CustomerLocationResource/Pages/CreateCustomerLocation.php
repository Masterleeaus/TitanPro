<?php

declare(strict_types=1);

namespace Modules\Dispatch\Filament\Resources\CustomerLocationResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Dispatch\Filament\Resources\CustomerLocationResource;

class CreateCustomerLocation extends CreateRecord
{
    protected static string $resource = CustomerLocationResource::class;
}
