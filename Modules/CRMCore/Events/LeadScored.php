<?php

namespace Modules\CRMCore\Events;

use Modules\CRMCore\Models\Lead;
use Illuminate\Foundation\Events\Dispatchable;

class LeadScored
{
    use Dispatchable;

    public function __construct(public Lead $lead, public int $score)
    {
    }
}
