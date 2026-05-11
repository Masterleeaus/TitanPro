<?php

namespace Modules\CleaningJobs\ControlPanel\Shortcuts;

use Modules\CleaningJobs\Actions\AI\GenerateChecklistFromKnowledgeAction;
use Modules\CleaningJobs\Actions\Booking\CreateBookingFromAgentAction;
use Modules\CleaningJobs\Actions\Booking\EstimateCleaningJobAction;
use Modules\CleaningJobs\Actions\Booking\LookupAvailabilityAction;
use Modules\CleaningJobs\Actions\ScheduleCleaningJob;
use Modules\CleaningJobs\Actions\StartJobTimesheet;

class ShortcutRegistry
{
    public static function getShortcuts(): array
    {
        return [
            [
                'key' => 'schedule_job',
                'label' => 'Schedule Job',
                'action' => ScheduleCleaningJob::class,
            ],
            [
                'key' => 'start_timesheet',
                'label' => 'Start Timesheet',
                'action' => StartJobTimesheet::class,
            ],
            [
                'key' => 'create_booking',
                'label' => 'Create Booking',
                'action' => CreateBookingFromAgentAction::class,
            ],
            [
                'key' => 'lookup_availability',
                'label' => 'Lookup Availability',
                'action' => LookupAvailabilityAction::class,
            ],
            [
                'key' => 'estimate_job',
                'label' => 'Estimate Job',
                'action' => EstimateCleaningJobAction::class,
            ],
            [
                'key' => 'generate_checklist',
                'label' => 'Generate Checklist',
                'action' => GenerateChecklistFromKnowledgeAction::class,
            ],
        ];
    }
}
