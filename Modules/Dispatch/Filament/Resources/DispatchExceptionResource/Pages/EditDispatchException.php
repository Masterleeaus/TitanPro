<?php

declare(strict_types=1);

namespace Modules\Dispatch\Filament\Resources\DispatchExceptionResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Modules\Dispatch\Filament\Resources\DispatchExceptionResource;

class EditDispatchException extends EditRecord
{
    protected static string $resource = DispatchExceptionResource::class;
}
