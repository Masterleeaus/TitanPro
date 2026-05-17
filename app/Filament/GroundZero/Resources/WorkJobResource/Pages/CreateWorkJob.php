<?php

namespace App\Filament\GroundZero\Resources\WorkJobResource\Pages;

use App\Filament\GroundZero\Resources\WorkJobResource;
use Filament\Resources\Pages\CreateRecord;

class CreateWorkJob extends CreateRecord
{
    protected static string $resource = WorkJobResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = auth()->user();

        abort_unless($user && $user->organization_id, 403);

        $data['company_id'] = $user->organization_id;
        $data['user_id']    = $user->id;
        return $data;
    }
}
