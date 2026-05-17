<?php

namespace Modules\ExampleModule\Queries;

class ExampleRecordQuery
{
    public function forTenant(int $companyId){ return \Modules\ExampleModule\Models\ExampleRecord::query()->forCompany($companyId); }
}
