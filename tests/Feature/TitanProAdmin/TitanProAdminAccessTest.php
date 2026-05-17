<?php

namespace Tests\Feature\TitanProAdmin;

use App\Models\Organization;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class TitanProAdminAccessTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        (new RolesAndPermissionsSeeder)->run();
    }

    #[Test]
    public function non_super_admin_receives_403_on_super_admin_routes(): void
    {
        $user = User::factory()->create();
        $user->assignRole('owner');

        $this->actingAs($user, 'super_admin')
            ->getJson('/api/titanpro-admin/health')
            ->assertForbidden();
    }

    #[Test]
    public function super_admin_can_toggle_modules_for_a_tenant(): void
    {
        $user = User::factory()->create();
        $user->assignRole('super_admin');
        $tenant = Organization::factory()->create(['enabled_modules' => []]);

        $this->actingAs($user, 'super_admin')
            ->postJson("/api/titanpro-admin/tenants/{$tenant->id}/modules/TitanCore/enable")
            ->assertOk()
            ->assertJsonPath('ok', true);

        $tenant->refresh();
        $this->assertContains('TitanCore', $tenant->enabled_modules ?? []);
    }

    #[Test]
    public function super_admin_can_suspend_tenant(): void
    {
        $user = User::factory()->create();
        $user->assignRole('super_admin');
        $tenant = Organization::factory()->create(['suspended_at' => null]);

        $this->actingAs($user, 'super_admin')
            ->postJson("/api/titanpro-admin/tenants/{$tenant->id}/suspend")
            ->assertOk()
            ->assertJsonPath('ok', true);

        $tenant->refresh();
        $this->assertNotNull($tenant->suspended_at);
    }
}
