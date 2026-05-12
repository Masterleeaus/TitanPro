<?php
declare(strict_types=1);
namespace Modules\CleaningJobs\Filament\Resources\CleaningJobResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\CleaningJobs\Filament\Resources\CleaningJobResource;

class CreateCleaningJob extends CreateRecord
{
    protected static string $resource = CleaningJobResource::class;
}
