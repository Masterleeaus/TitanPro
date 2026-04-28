<?php

namespace Modules\CRMCore\Actions;

use Modules\CRMCore\Models\Lead;
use Modules\CRMCore\Events\LeadScored;
use Modules\CRMCore\Services\LeadScoringService;

class ScoreLead
{
    public function handle(Lead $lead)
    {
        $score = app(LeadScoringService::class)->persist($lead);

        event(new LeadScored($lead, $score->value));

        return $score;
    }
}
