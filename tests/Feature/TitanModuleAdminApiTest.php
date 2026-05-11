<?php

use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Nwidart\Modules\Facades\Module;

beforeEach(function () {
    (new RolesAndPermissionsSeeder)->run();
});

function fakeModule(string $name = 'TitanCore', bool $enabled = true): object
{
    return new class($name, $enabled)
    {
        public function __construct(private string $name, private bool $enabled) {}

        public function getName(): string
        {
            return $this->name;
        }

        public function isEnabled(): bool
        {
            return $this->enabled;
        }

        public function json(): object
        {
            return new class($this->name)
            {
                public function __construct(private string $name) {}
                public function toArray(): array
                {
                    return [
                        'alias' => strtolower($this->name),
                        'version' => '1.0.0',
                        'description' => 'Test module',
                    ];
                }
            };
        }

        public function enable(): void
        {
            $this->enabled = true;
        }

        public function disable(): void
        {
            $this->enabled = false;
        }
    };
}

test('module admin API returns 401 for unauthenticated requests', function () {
    $this->getJson('/admin/titan/modules')->assertUnauthorized();
    $this->postJson('/admin/titan/modules/TitanCore/enable')->assertUnauthorized();
    $this->postJson('/admin/titan/modules/TitanCore/disable')->assertUnauthorized();
    $this->getJson('/admin/titan/modules/TitanCore/health')->assertUnauthorized();
});

test('module admin API returns 403 for non-admin users', function () {
    $user = User::factory()->create();
    $user->assignRole('owner');

    $this->actingAs($user)->getJson('/admin/titan/modules')->assertForbidden();
});

test('GET admin modules returns module list for super_admin', function () {
    $user = User::factory()->create();
    $user->assignRole('super_admin');

    Module::shouldReceive('all')->once()->andReturn([fakeModule('TitanCore', true)]);

    $this->actingAs($user)
        ->getJson('/admin/titan/modules')
        ->assertOk()
        ->assertJsonPath('data.0.name', 'TitanCore')
        ->assertJsonPath('data.0.enabled', true);
});

test('POST enable and disable endpoints toggle module for super_admin', function () {
    $user = User::factory()->create();
    $user->assignRole('super_admin');

    $module = fakeModule('TitanCore', false);

    Module::shouldReceive('find')->with('TitanCore')->twice()->andReturn($module);

    $this->actingAs($user)
        ->postJson('/admin/titan/modules/TitanCore/enable')
        ->assertOk()
        ->assertJsonPath('enabled', true);

    $this->actingAs($user)
        ->postJson('/admin/titan/modules/TitanCore/disable')
        ->assertOk()
        ->assertJsonPath('enabled', false);
});

test('GET health endpoint returns module health payload for super_admin', function () {
    $user = User::factory()->create();
    $user->assignRole('super_admin');

    Module::shouldReceive('find')->with('TitanCore')->once()->andReturn(fakeModule('TitanCore', true));

    $this->actingAs($user)
        ->getJson('/admin/titan/modules/TitanCore/health')
        ->assertOk()
        ->assertJsonPath('module', 'TitanCore');
});

test('GET manifests endpoint returns parsed manifest for super_admin', function () {
    $user = User::factory()->create();
    $user->assignRole('super_admin');

    Module::shouldReceive('find')->with('TitanCore')->once()->andReturn(fakeModule('TitanCore', true));

    $this->actingAs($user)
        ->getJson('/admin/titan/modules/TitanCore/manifests')
        ->assertOk()
        ->assertJsonPath('module', 'TitanCore')
        ->assertJsonStructure(['module', 'manifest']);
});

test('GET manifests endpoint returns 401 for unauthenticated requests', function () {
    $this->getJson('/admin/titan/modules/TitanCore/manifests')->assertUnauthorized();
});

test('GET manifests endpoint returns 403 for non-admin users', function () {
    $user = User::factory()->create();
    $user->assignRole('owner');

    $this->actingAs($user)->getJson('/admin/titan/modules/TitanCore/manifests')->assertForbidden();
});

test('GET manifests endpoint returns 404 for unknown module', function () {
    $user = User::factory()->create();
    $user->assignRole('super_admin');

    Module::shouldReceive('find')->with('NonExistent')->once()->andReturnNull();
    Module::shouldReceive('all')->once()->andReturn([]);

    $this->actingAs($user)
        ->getJson('/admin/titan/modules/NonExistent/manifests')
        ->assertNotFound();
});

test('POST sync endpoint triggers manifest reload and returns module list for super_admin', function () {
    $user = User::factory()->create();
    $user->assignRole('super_admin');

    Module::shouldReceive('all')->once()->andReturn([fakeModule('TitanCore', true)]);

    $this->actingAs($user)
        ->postJson('/admin/titan/modules/sync')
        ->assertOk()
        ->assertJsonPath('ok', true)
        ->assertJsonStructure(['ok', 'data'])
        ->assertJsonPath('data.0.name', 'TitanCore');
});

test('POST sync endpoint returns 401 for unauthenticated requests', function () {
    $this->postJson('/admin/titan/modules/sync')->assertUnauthorized();
});

test('POST sync endpoint returns 403 for non-admin users', function () {
    $user = User::factory()->create();
    $user->assignRole('owner');

    $this->actingAs($user)->postJson('/admin/titan/modules/sync')->assertForbidden();
});

test('platform modules dashboard is accessible for super_admin', function () {
    $user = User::factory()->create();
    $user->assignRole('super_admin');

    Module::shouldReceive('all')->once()->andReturn([fakeModule('TitanCore', true)]);

    $this->actingAs($user)
        ->get('/platform/modules')
        ->assertOk();
});

test('platform modules dashboard returns 403 for non-admin users', function () {
    $user = User::factory()->create();
    $user->assignRole('owner');

    $this->actingAs($user)
        ->get('/platform/modules')
        ->assertForbidden();
});

test('platform modules dashboard redirects unauthenticated users', function () {
    $this->get('/platform/modules')
        ->assertRedirect('/login');
});
