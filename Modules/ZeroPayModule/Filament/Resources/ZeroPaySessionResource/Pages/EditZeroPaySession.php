<?php

namespace Modules\ZeroPayModule\Filament\Resources\ZeroPaySessionResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Modules\ZeroPayModule\Actions\UpdateZeroPaySessionAction;
use Modules\ZeroPayModule\Data\ZeroPaySessionData;
use Modules\ZeroPayModule\Filament\Resources\ZeroPaySessionResource;

class EditZeroPaySession extends EditRecord
{
    protected static string $resource = ZeroPaySessionResource::class;

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $data['company_id'] = $record->company_id;
        $data['user_id'] = $record->user_id;
        $data['name'] = $record->name ?? $record->session_token;

        return app(UpdateZeroPaySessionAction::class)->execute($record, ZeroPaySessionData::fromArray($data));
    }
}
