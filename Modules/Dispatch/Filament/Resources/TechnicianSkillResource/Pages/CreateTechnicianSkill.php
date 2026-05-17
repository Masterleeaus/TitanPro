<?php

declare(strict_types=1);

namespace Modules\Dispatch\Filament\Resources\TechnicianSkillResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Dispatch\Filament\Resources\TechnicianSkillResource;

class CreateTechnicianSkill extends CreateRecord
{
    protected static string $resource = TechnicianSkillResource::class;
}
