<?php

namespace Modules\CRMCore\Filament\Resources\DealResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\CRMCore\Actions\Deal\CreateDealAction;
use Modules\CRMCore\Filament\Resources\DealResource;
use Modules\CRMCore\Models\Deal;

class CreateDeal extends CreateRecord
{
    protected static string $resource = DealResource::class;

    protected function handleRecordCreation(array $data): Deal
    {
        return app(CreateDealAction::class)->handle($data);
    }
}
