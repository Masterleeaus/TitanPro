<?php

namespace Modules\Security\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Security\Actions\Approvals\ApproveSecurityRecordAction;
use Modules\Security\Actions\Approvals\ValidateSecurityRecordAction;
use Modules\Security\Workflows\Guards\SecurityWorkflowGuard;

class WorkflowServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(SecurityWorkflowGuard::class);
        $this->app->singleton(ApproveSecurityRecordAction::class);
        $this->app->singleton(ValidateSecurityRecordAction::class);
    }
}
