<?php

namespace Modules\CRMCore\Repositories;

use Modules\CRMCore\Models\Deal;
use Modules\CRMCore\Models\Lead;

class PipelineRepository
{
    public function leads()
    {
        return Lead::query();
    }

    public function deals()
    {
        return Deal::query();
    }
}
