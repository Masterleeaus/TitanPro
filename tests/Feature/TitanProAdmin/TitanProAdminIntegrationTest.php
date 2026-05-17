<?php

namespace Tests\Feature\TitanProAdmin;

use App\Models\Organization;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\TitanProAdmin\Actions\DisableModuleAction;
use Modules\TitanProAdmin\Actions\EnableModuleAction;
use Modules\TitanProAdmin\Actions\SuspendTenantAction;
use Modules\TitanProAdmin\Models\AdminAuditLog;
use Modules\TitanProAdmin\Providers\TitanProAdminServiceProvider;
use Modules\TitanProAdmin\Services\LicenseService;
use Modules\TitanProAdmin\Services\ModuleToggleService;
use Modules\TitanProAdmin\Services\PlatformHealthService;
use Modules\TitanProAdmin\Services\TenantService;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class TitanProAdminIntegrationTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function provider_registers_guard_and_core_services(): void
    {
        (new TitanProAdminServiceProvider($this->app))->register();

        $guards = config('auth.guards', []);

        $this->assertArrayHasKey('super_admin', $guards);
        $this->assertTrue($this->app->bound(TenantService::class));
        $this->assertTrue($this->app->bound(ModuleToggleService::class));
        $this->assertTrue($this->app->bound(LicenseService::class));
        $this->assertTrue($this->app->bound(PlatformHealthService::class));
    }

    #[Test]
    public function every_state_changing_action_writes_admin_audit_log(): void
    {
        $tenant = Organization::factory()->create(['enabled_modules' => ['TitanCore']]);

        app(EnableModuleAction::class)->execute($tenant->id, 'TitanNexus', 11);
        app(DisableModuleAction::class)->execute($tenant->id, 'TitanCore', 11);
        app(SuspendTenantAction::class)->execute($tenant->id, 11);

        $actions = AdminAuditLog::query()->orderBy('id')->pluck('action')->all();

        $this->assertSame([
            'module.enable',
            'module.disable',
            'tenant.suspend',
        ], $actions);
    }
}
