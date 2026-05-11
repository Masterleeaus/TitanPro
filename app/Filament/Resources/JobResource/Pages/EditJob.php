<?php

namespace App\Filament\Resources\JobResource\Pages;

use App\Filament\Resources\JobResource;
use App\Models\Job;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Validation\ValidationException;

class EditJob extends EditRecord
{
    protected static string $resource = JobResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data = JobResource::prepareFormData($data);

        $current = (string) $this->record->status;
        $next = (string) ($data['status'] ?? $current);

        if (! Job::canTransitionInAdminWorkflow($current, $next)) {
            throw ValidationException::withMessages([
                'data.status' => 'Invalid status transition. Allowed workflow: Scheduled → In Progress → Completed.',
            ]);
        }

        return $data;
    }
}
