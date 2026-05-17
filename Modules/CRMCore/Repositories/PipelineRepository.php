<?php

namespace Modules\CRMCore\Repositories;

use Illuminate\Support\Facades\Auth;
use Modules\CRMCore\Models\Deal;
use Modules\CRMCore\Interfaces\PipelineMetricProvider;
use Modules\CRMCore\Models\Lead;

class PipelineRepository implements PipelineMetricProvider
{
    public function leads()
    {
        return Lead::query()
            ->when($this->resolveTenantId(), fn ($query, $tenantId) => $query->where('tenant_id', (string) $tenantId));
    }

    public function deals()
    {
        return Deal::query()
            ->when($this->resolveTenantId(), fn ($query, $tenantId) => $query->where('tenant_id', (string) $tenantId));
    }

    public function metrics(): array
    {
        return [
            'leads' => $this->leads()->count(),
            'deals' => $this->deals()->count(),
        ];
    }

    private function resolveTenantId(): string|int|null
    {
        $requestTenant = request()?->header('X-Tenant-Id');
        if ($requestTenant !== null && $requestTenant !== '') {
            return $requestTenant;
        }

        $user = Auth::user();
        if (! $user) {
            return function_exists('company') ? company()?->id : null;
        }

        return $user->tenant_id
            ?? $user->company_id
            ?? $user->organization_id;
    }
}
