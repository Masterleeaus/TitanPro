<?php

namespace Modules\CRMCore\Filament\Resources\DealResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Modules\CRMCore\Actions\Deal\UpdateDealAction;
use Modules\CRMCore\Filament\Resources\DealResource;
use Modules\CRMCore\Models\Deal;

class EditDeal extends EditRecord
{
    protected static string $resource = DealResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Deal
    {
        /** @var Deal $record */
        return app(UpdateDealAction::class)->handle($record, $data);
    }
}
