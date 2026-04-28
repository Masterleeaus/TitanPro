<?php

namespace Modules\CRMCore\Jobs;

use Modules\CRMCore\Models\Lead;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\CRMCore\Actions\ScoreLead;

class ScoreLeadJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public int $leadId)
    {
    }

    public function handle(): void
    {
        $lead = Lead::find($this->leadId);

        if ($lead) {
            app(ScoreLead::class)->handle($lead);
        }
    }
}
