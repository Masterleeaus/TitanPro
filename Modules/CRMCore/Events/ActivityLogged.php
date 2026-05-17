<?php

namespace Modules\CRMCore\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Modules\CRMCore\Models\CRMCoreActivityLog;

class ActivityLogged
{
    use Dispatchable;

    public function __construct(public CRMCoreActivityLog $activityLog)
    {
    }
}
