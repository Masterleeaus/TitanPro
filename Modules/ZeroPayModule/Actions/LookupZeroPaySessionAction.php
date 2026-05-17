<?php

namespace Modules\ZeroPayModule\Actions;

use Modules\ZeroPayModule\Models\ZeroPaySession;

class LookupZeroPaySessionAction
{
    public function execute(int|string $id, int|string $companyId): ?ZeroPaySession
    {
        return ZeroPaySession::query()->where('company_id', $companyId)->whereKey($id)->first();
    }
}
