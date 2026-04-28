<?php

namespace Modules\CRMCore\Workflows\Transitions;

use Modules\CRMCore\Models\Deal;

class DealWonToProjectReady
{
    public function applies(Deal $deal): bool
    {
        return filled($deal->won_at ?? null) && blank($deal->crmcore_project_id ?? null);
    }
}
