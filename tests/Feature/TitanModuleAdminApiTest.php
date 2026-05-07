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

    $this->artisan('modules:health', ['--module' => 'TitanCore', '--json' => true]);

    $this->actingAs($user)
        ->getJson('/admin/titan/modules/TitanCore/health')
        ->assertOk()
        ->assertJsonPath('module', 'TitanCore');
});
