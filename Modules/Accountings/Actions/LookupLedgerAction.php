<?php

namespace Modules\Accountings\Actions;

use Illuminate\Auth\Access\AuthorizationException;
use Modules\Accountings\Entities\Journal;

class LookupLedgerAction
{
    public function execute(array $filters = [])
    {
        $companyId = $this->resolveCompanyId(isset($filters['company_id']) ? (int) $filters['company_id'] : null);
        $limit = max(1, min((int) ($filters['limit'] ?? 50), 200));

        return Journal::query()
            ->where('company_id', $companyId)
            ->latest()
            ->limit($limit)
            ->get();
    }

    private function resolveCompanyId(?int $requestedCompanyId = null): int
    {
        $authCompanyId = auth()->user()->company_id ?? null;

        if ($authCompanyId !== null) {
            if ($requestedCompanyId !== null && $requestedCompanyId !== (int) $authCompanyId) {
                throw new AuthorizationException('Cross-tenant ledger lookup is not allowed.');
            }

            return (int) $authCompanyId;
        }

        if ($requestedCompanyId !== null) {
            return $requestedCompanyId;
        }

        throw new AuthorizationException('Company context is required.');
    }
}
