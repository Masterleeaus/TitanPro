<?php

namespace Modules\CleaningJobs\ControlPanel\Shortcuts;

use Modules\CleaningJobs\Actions\AI\EscalateVisitRiskAction;
use Modules\CleaningJobs\Actions\AI\GenerateChecklistFromKnowledgeAction;
use Modules\CleaningJobs\Actions\Booking\CreateBookingFromAgentAction;
use Modules\CleaningJobs\Actions\Booking\EstimateCleaningJobAction;
use Modules\CleaningJobs\Actions\Booking\LookupAvailabilityAction;

class ShortcutRegistry
{
    public static function getShortcuts(): array
    {
        return [
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
            [
                'key' => 'escalate_visit_risk',
                'label' => 'Escalate Visit Risk',
                'action' => EscalateVisitRiskAction::class,
            ],
        ];
    }
}
