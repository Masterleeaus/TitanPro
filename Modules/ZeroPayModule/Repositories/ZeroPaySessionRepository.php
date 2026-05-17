<?php

namespace Modules\ZeroPayModule\Repositories;

use Modules\ZeroPayModule\Models\ZeroPaySession;

class ZeroPaySessionRepository
{
    public function findForCompany(int $id, int $companyId)
    {
        return ZeroPaySession::query()->forCompany($companyId)->find($id);
    }
}
