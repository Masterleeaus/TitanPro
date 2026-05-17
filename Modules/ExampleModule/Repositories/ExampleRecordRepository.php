<?php

namespace Modules\ExampleModule\Repositories;

class ExampleRecordRepository
{
    public function findForCompany(int $id,int $companyId){ return \Modules\ExampleModule\Models\ExampleRecord::query()->forCompany($companyId)->find($id); }
}
