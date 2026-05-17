<?php

namespace Modules\ZeroPayModule\Queries;

use Modules\ZeroPayModule\Models\ZeroPaySession;

class ZeroPaySessionQuery
{
    public function forTenant(int $companyId)
    {
        return ZeroPaySession::query()->forCompany($companyId);
    }
}
