<?php

declare(strict_types=1);

namespace Modules\Budgeting\Filament\Resources\ApprovalThresholdResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Modules\Budgeting\Filament\Resources\ApprovalThresholdResource;

class EditApprovalThreshold extends EditRecord
{
    protected static string $resource = ApprovalThresholdResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
