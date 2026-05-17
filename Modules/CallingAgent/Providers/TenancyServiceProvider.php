<?php

namespace Modules\CallingAgent\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use Modules\CallingAgent\Models\CallingAgentActiveCall;
use Modules\CallingAgent\Scopes\TenantScope;
use Modules\CallingAgent\Models\CallingAgent;
use Modules\CallingAgent\Models\CallingAgentCall;
use Modules\CallingAgent\Models\CallingAgentCallOutcome;
use Modules\CallingAgent\Models\CallingAgentCallerProfile;
use Modules\CallingAgent\Models\CallingAgentMessage;
use Modules\CallingAgent\Models\CallingAgentPhoneNumber;
use Modules\CallingAgent\Models\CallingAgentUsageRecord;
use Modules\CallingAgent\Support\TenantContext;

class TenancyServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../Config/tenancy.php', 'calling-agent.tenancy');
    }

    public function boot(): void
    {
        if (config('calling-agent.tenancy.enabled', true)) {
            foreach ($this->tenantScopedModels() as $modelClass) {
                $modelClass::addGlobalScope(new TenantScope);
                $modelClass::creating(function (Model $model): void {
                    if (blank($model->getAttribute('tenant_id'))) {
                        $tenantId = TenantContext::id();

                        if ($tenantId !== null) {
                            $model->setAttribute('tenant_id', $tenantId);
                        }
                    }
                });
            }
        }
    }

    /**
     * @return array<class-string<Model>>
     */
    private function tenantScopedModels(): array
    {
        return [
            CallingAgent::class,
            CallingAgentPhoneNumber::class,
            CallingAgentCall::class,
            CallingAgentActiveCall::class,
            CallingAgentMessage::class,
            CallingAgentUsageRecord::class,
            CallingAgentCallerProfile::class,
            CallingAgentCallOutcome::class,
        ];
    }
}
