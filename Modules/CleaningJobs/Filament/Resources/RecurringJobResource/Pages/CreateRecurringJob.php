<?php
declare(strict_types=1);
namespace Modules\CleaningJobs\Filament\Resources\RecurringJobResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\CleaningJobs\Filament\Resources\RecurringJobResource;

class CreateRecurringJob extends CreateRecord
{
    protected static string $resource = RecurringJobResource::class;
}
