<?php

declare(strict_types=1);

namespace Modules\Dispatch\Filament\Resources\TechnicianSkillResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Modules\Dispatch\Filament\Resources\TechnicianSkillResource;

class ListTechnicianSkills extends ListRecords
{
    protected static string $resource = TechnicianSkillResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
