<?php

namespace Modules\CRMCore\Actions\Deal;

use Modules\CRMCore\Events\DealWon;
use Modules\CRMCore\Models\Deal;
use Modules\CRMCore\Models\DealStage;

class WinDealAction
{
    /**
     * Mark a deal as won, optionally moving it to a won stage.
     *
     * @param array<string, mixed> $overrides
     */
    public function handle(Deal $deal, array $overrides = []): Deal
    {
        if (filled($deal->won_at)) {
            return $deal;
        }

        $wonStageId = DealStage::query()
            ->where('pipeline_id', $deal->pipeline_id)
            ->where('is_won_stage', true)
            ->value('id') ?? $deal->deal_stage_id;

        $deal->forceFill(array_merge([
            'won_at'       => now(),
            'deal_stage_id'=> $wonStageId,
            'closed_at'    => now(),
        ], $overrides))->save();

        DealWon::dispatch($deal->fresh());

        return $deal->refresh();
    }
}
