<?php

namespace Tests\Unit\TitanProAdmin;

use App\Models\Organization;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\TitanProAdmin\Actions\EnableModuleAction;
use Modules\TitanProAdmin\Actions\SuspendTenantAction;
use Modules\TitanProAdmin\Models\AdminAuditLog;
use Modules\TitanProAdmin\Services\PlatformHealthService;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class TitanProAdminActionsTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function enable_module_action_updates_toggle_and_writes_audit_log(): void
    {
        $tenant = Organization::factory()->create(['enabled_modules' => []]);

        $enabledModules = app(EnableModuleAction::class)->execute($tenant->id, 'TitanCore', 99);

        $tenant->refresh();

        $this->assertContains('TitanCore', $enabledModules);
        $this->assertContains('TitanCore', $tenant->enabled_modules ?? []);
        $this->assertDatabaseHas('titan_admin_audit_logs', [
            'actor_id' => 99,
            'target_company_id' => $tenant->id,
            'action' => 'module.enable',
        ]);
    }

    #[Test]
    public function suspend_tenant_action_sets_suspended_at_and_writes_audit_log(): void
    {
        $tenant = Organization::factory()->create(['suspended_at' => null]);

        app(SuspendTenantAction::class)->execute($tenant->id, 7);

        $tenant->refresh();

        $this->assertNotNull($tenant->suspended_at);
        $this->assertDatabaseHas('titan_admin_audit_logs', [
            'actor_id' => 7,
            'target_company_id' => $tenant->id,
            'action' => 'tenant.suspend',
        ]);
    }

    #[Test]
    public function platform_health_service_returns_core_health_keys(): void
    {
        $health = app(PlatformHealthService::class)->status();

        $this->assertArrayHasKey('database', $health);
        $this->assertArrayHasKey('cache', $health);
        $this->assertArrayHasKey('queue', $health);
        $this->assertArrayHasKey('timestamp', $health);
    }
}
