<?php

namespace Modules\CleaningJobs\Filament\Resources\JobResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\CleaningJobs\Filament\Resources\JobResource;

class CreateJob extends CreateRecord
{
    protected static string $resource = JobResource::class;
}
