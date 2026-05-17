<?php

namespace Modules\ExampleModule\Support;

class TenantContext
{
    public function companyId(): ?int { return function_exists("tenant_company_id") ? tenant_company_id() : null; }
}
