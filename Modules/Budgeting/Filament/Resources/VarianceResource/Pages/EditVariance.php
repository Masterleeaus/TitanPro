<?php

declare(strict_types=1);

namespace Modules\Budgeting\Filament\Resources\VarianceResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Modules\Budgeting\Filament\Resources\VarianceResource;

class EditVariance extends EditRecord
{
    protected static string $resource = VarianceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
