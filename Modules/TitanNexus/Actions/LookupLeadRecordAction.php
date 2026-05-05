<?php
namespace Modules\TitanNexus\Actions;

use Modules\TitanNexus\Models\LeadRecord;

class LookupLeadRecordAction
{
    public function execute(int|string $id, int|string $companyId): ?LeadRecord
    {
        return LeadRecord::query()->where('company_id', $companyId)->whereKey($id)->first();
    }
}
