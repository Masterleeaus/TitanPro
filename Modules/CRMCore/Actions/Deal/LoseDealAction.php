<?php

namespace Modules\CRMCore\Actions\Deal;

use Modules\CRMCore\Events\DealLost;
use Modules\CRMCore\Models\Deal;
use Modules\CRMCore\Models\DealStage;

class LoseDealAction
{
    /**
     * Mark a deal as lost, optionally moving it to a lost stage.
     *
     * @param array<string, mixed> $overrides
     */
    public function handle(Deal $deal, ?string $lostReason = null, array $overrides = []): Deal
    {
        if (filled($deal->lost_at)) {
            return $deal;
        }

        $lostStageId = DealStage::query()
            ->where('pipeline_id', $deal->pipeline_id)
            ->where('is_lost_stage', true)
            ->value('id') ?? $deal->deal_stage_id;

        $deal->forceFill(array_merge([
            'lost_at'      => now(),
            'lost_reason'  => $lostReason,
            'deal_stage_id'=> $lostStageId,
            'closed_at'    => now(),
        ], $overrides))->save();

        DealLost::dispatch($deal->fresh());

        return $deal->refresh();
    }
}
