<?php

namespace Modules\ZeroPayModule\Filament\Resources\ZeroPaySessionResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Modules\ZeroPayModule\Actions\CreateZeroPaySessionAction;
use Modules\ZeroPayModule\Data\ZeroPaySessionData;
use Modules\ZeroPayModule\Filament\Resources\ZeroPaySessionResource;

class CreateZeroPaySession extends CreateRecord
{
    protected static string $resource = ZeroPaySessionResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $user = auth()->user();

        abort_if(! $user, 401, 'Unauthenticated.');
        abort_if(empty($user->company_id), 422, 'User must be assigned to a company before creating payment sessions.');

        $data['session_token'] = $data['session_token'] ?? Str::uuid()->toString();
        $data['company_id'] = $user->company_id;
        $data['user_id'] = $user->id;
        $data['name'] = $data['session_token'];

        return app(CreateZeroPaySessionAction::class)->execute(ZeroPaySessionData::fromArray($data));
    }
}
