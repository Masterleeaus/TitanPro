<?php

namespace Modules\ZeroPayModule\Services;

use Modules\ZeroPayModule\Models\ZeroPaySession;
use Modules\ZeroPayModule\Services\Contracts\ZeroPayModuleServiceContract;

class ZeroPayModuleService implements ZeroPayModuleServiceContract
{
    public function listForCompany(int $companyId, int $perPage = 25)
    {
        return ZeroPaySession::query()->forCompany($companyId)->latest()->paginate($perPage);
    }
}
