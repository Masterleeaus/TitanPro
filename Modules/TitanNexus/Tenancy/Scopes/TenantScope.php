<?php

namespace Modules\TitanNexus\Tenancy\Scopes;

/** Applies company_id tenant isolation. */
class TenantScope
{
    public function handle(): array
    {
        return ['enabled' => true, 'purpose' => 'Applies company_id tenant isolation.'];
    }
}
