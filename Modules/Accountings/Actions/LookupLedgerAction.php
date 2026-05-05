<?php

namespace Modules\Accountings\Actions;

use Modules\Accountings\Entities\Journal;

class LookupLedgerAction
{
    public function execute(array $filters = [])
    {
        return Journal::query()->when($filters['company_id'] ?? null, fn($q, $companyId) => $q->where('company_id', $companyId))->latest()->limit($filters['limit'] ?? 50)->get();
    }
}
