<?php

namespace Modules\TitanNexus\Queries;

class LeadRecordQuery
{
    public function forTenant(int $companyId){ return \Modules\TitanNexus\Models\LeadRecord::query()->forCompany($companyId); }
}
