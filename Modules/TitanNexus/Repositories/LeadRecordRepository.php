<?php

namespace Modules\TitanNexus\Repositories;

class LeadRecordRepository
{
    public function findForCompany(int $id,int $companyId){ return \Modules\TitanNexus\Models\LeadRecord::query()->forCompany($companyId)->find($id); }
}
