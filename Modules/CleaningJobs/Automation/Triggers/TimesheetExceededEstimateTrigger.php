<?php
namespace Modules\CleaningJobs\Automation\Triggers;
class TimesheetExceededEstimateTrigger
{
    public function matches(float $estimatedHours, float $actualHours): bool
    {
        return $estimatedHours > 0 && $actualHours > $estimatedHours;
    }
}
