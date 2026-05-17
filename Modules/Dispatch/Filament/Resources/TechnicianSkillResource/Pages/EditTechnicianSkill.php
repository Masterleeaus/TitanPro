<?php

declare(strict_types=1);

namespace Modules\Dispatch\Filament\Resources\TechnicianSkillResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Modules\Dispatch\Filament\Resources\TechnicianSkillResource;

class EditTechnicianSkill extends EditRecord
{
    protected static string $resource = TechnicianSkillResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
