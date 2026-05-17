<?php

namespace Modules\CRMCore\Listeners;

use Modules\CRMCore\Enums\PipelineSignal;
use Modules\CRMCore\Actions\LogCRMActivity;
use Modules\CRMCore\Events\LeadScored;

class RecordLeadScored
{
    public function handle(LeadScored $event): void
    {
        app(LogCRMActivity::class)->handle(PipelineSignal::LeadScored->value, $event->lead, [
            'score' => $event->score,
        ]);
    }
}
