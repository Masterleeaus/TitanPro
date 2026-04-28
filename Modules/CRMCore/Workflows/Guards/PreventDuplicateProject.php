<?php

namespace Modules\CRMCore\Workflows\Guards;

use Modules\CRMCore\Models\Deal;

class PreventDuplicateProject
{
    public function allows(Deal $deal): bool
    {
        return blank($deal->crmcore_project_id ?? null);
    }
}
