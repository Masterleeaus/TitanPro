<?php

namespace Modules\CRMCore\Actions;

use App\Models\Customer;
use Modules\CRMCore\Events\DealConvertedToProject;
use Modules\CRMCore\Models\Deal;
use Modules\CRMCore\Support\HostProjectBridge;

class CreateProjectFromDeal
{
    public function handle(Deal $deal, array $overrides = [])
    {
        if (filled($deal->crmcore_project_id ?? null)) { return HostProjectBridge::query()->find($deal->crmcore_project_id); }

        $this->assertTenantAccess($deal);

        $customer = filled($deal->crmcore_customer_id ?? null) ? Customer::query()->find($deal->crmcore_customer_id) : null;
        if (! $customer) { throw new \RuntimeException('Select a site customer on the deal before creating a job.'); }
        $payload = array_merge([
            'organization_id' => $customer->organization_id,
            'customer_id' => $customer->getKey(),
            'title' => $deal->title ?? ('Job from Deal #' . $deal->getKey()),
            'description' => $deal->description ?? null,
            'status' => 'scheduled',
            'scheduled_at' => now()->addWeek(),
            'office_notes' => 'Created from CRMCore deal #' . $deal->getKey(),
        ], $overrides);
        $project = HostProjectBridge::create(array_filter($payload, fn ($value) => $value !== null));
        $deal->forceFill(['crmcore_project_id' => $project->getKey(), 'crmcore_converted_to_project_at' => now()])->save();
        event(new DealConvertedToProject($deal, $project));
        return $project;
    }

    private function assertTenantAccess(Deal $deal): void
    {
        $user = auth()->user();
        if (! $user) {
            return;
        }

        $userTenant = $user->tenant_id ?? $user->company_id ?? $user->organization_id ?? null;
        if ($userTenant === null) {
            return;
        }

        if (filled($deal->tenant_id ?? null) && (string) $deal->tenant_id !== (string) $userTenant) {
            throw new \RuntimeException('Deal tenant scope mismatch.');
        }

        if (filled($deal->company_id ?? null) && (string) $deal->company_id !== (string) $userTenant) {
            throw new \RuntimeException('Deal company scope mismatch.');
        }
    }
}
