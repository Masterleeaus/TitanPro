<?php

declare(strict_types=1);

namespace Modules\Dispatch\Filament\Resources\DispatchExceptionResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Dispatch\Filament\Resources\DispatchExceptionResource;

class CreateDispatchException extends CreateRecord
{
    protected static string $resource = DispatchExceptionResource::class;
}
