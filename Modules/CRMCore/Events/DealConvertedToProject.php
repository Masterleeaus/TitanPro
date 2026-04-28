<?php

namespace Modules\CRMCore\Events;

use Modules\CRMCore\Models\Deal;
use App\Models\Job;
use Illuminate\Foundation\Events\Dispatchable;

class DealConvertedToProject
{
    use Dispatchable;

    public function __construct(public Deal $deal, public Job $project)
    {
    }
}
