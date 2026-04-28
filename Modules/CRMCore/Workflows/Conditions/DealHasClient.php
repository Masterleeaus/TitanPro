<?php

namespace Modules\CRMCore\Workflows\Conditions;

use Modules\CRMCore\Models\Deal;

class DealHasClient
{
    public function passes(Deal $deal): bool
    {
        return filled($deal->client_id ?? null);
    }
}
