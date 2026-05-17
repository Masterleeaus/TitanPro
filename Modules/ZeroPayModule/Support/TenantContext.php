<?php

namespace Modules\ZeroPayModule\Support;

class TenantContext
{
    public function companyId(): ?int
    {
        return function_exists('tenant_company_id') ? tenant_company_id() : null;
    }
}
