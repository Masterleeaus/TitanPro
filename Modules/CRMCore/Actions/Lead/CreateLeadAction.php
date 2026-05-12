<?php

namespace Modules\CRMCore\Actions\Lead;

use Modules\CRMCore\Models\Lead;
use Modules\CRMCore\Models\LeadSource;
use Modules\CRMCore\Models\LeadStatus;
use Modules\CRMCore\Scopes\ScopedByCompany;

class CreateLeadAction
{
    /**
     * Create a new tenant-scoped lead.
     *
     * @param array<string, mixed> $data
     */
    public function handle(array $data): Lead
    {
        $companyId = $data['company_id'] ?? ScopedByCompany::resolveCompanyId();

        if (! is_numeric($companyId)) {
            throw new \RuntimeException('company_id is required to create a lead.');
        }

        $data['company_id'] = (int) $companyId;

        $data['lead_status_id'] ??= $this->defaultLeadStatusId();
        $data['lead_source_id'] ??= $this->defaultLeadSourceId();

        return Lead::create($data);
    }

    private function defaultLeadStatusId(): ?int
    {
        return LeadStatus::query()->where('is_default', true)->value('id')
            ?? LeadStatus::query()->orderBy('position')->value('id');
    }

    private function defaultLeadSourceId(): ?int
    {
        return LeadSource::query()->orderBy('name')->value('id');
    }
}
