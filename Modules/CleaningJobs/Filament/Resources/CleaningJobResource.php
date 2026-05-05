<?php
namespace Modules\CleaningJobs\Filament\Resources;
class CleaningJobResource
{
    public static function capabilities(): array
    {
        return ['jobs','appointments','crew','timesheets','kanban','comments','files','financials'];
    }
}
