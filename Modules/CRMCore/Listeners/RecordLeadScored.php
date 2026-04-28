<?php

namespace Modules\CRMCore\Listeners;

use Modules\CRMCore\Actions\LogCRMActivity;
use Modules\CRMCore\Events\LeadScored;

class RecordLeadScored
{
    public function handle(LeadScored $event): void
    {
        app(LogCRMActivity::class)->handle('lead_scored', $event->lead, [
            'score' => $event->score,
        ]);
    }
}
