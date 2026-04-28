<?php

namespace Modules\CRMCore\Services;

use Modules\CRMCore\Models\Lead;
use Illuminate\Support\Facades\Schema;
use Modules\CRMCore\ValueObjects\PipelineScore;

class LeadScoringService
{
    public function score(Lead $lead): PipelineScore
    {
        $score = 0;

        if (filled($lead->client_email ?? null)) {
            $score += 20;
        }

        if (filled($lead->mobile ?? null) || filled($lead->cell ?? null)) {
            $score += 20;
        }

        if ((float) ($lead->value ?? 0) > 0 || (float) ($lead->total_value ?? 0) > 0) {
            $score += 25;
        }

        if (filled($lead->client_id ?? null)) {
            $score += 20;
        }

        if (filled($lead->next_follow_up ?? null)) {
            $score += 15;
        }

        return new PipelineScore($score);
    }

    public function persist(Lead $lead): PipelineScore
    {
        $score = $this->score($lead);

        if (Schema::hasColumn('leads', 'crmcore_score')) {
            $lead->forceFill(['crmcore_score' => $score->value])->save();
        }

        return $score;
    }
}
