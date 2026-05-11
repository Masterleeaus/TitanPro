<?php

use App\Models\Organization;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;

beforeEach(function () {
    (new RolesAndPermissionsSeeder)->run();
});

function titanProResourceUser(string $role): User
{
    $organization = Organization::factory()->create();
    $user = User::factory()->create(['organization_id' => $organization->id]);
    $user->assignRole($role);

    return $user;
}

it('allows super_admin access to TitanPro dedicated resources and health page', function (string $path) {
    $this->actingAs(titanProResourceUser('super_admin'))
        ->get($path)
        ->assertOk();
})->with([
    '/titanpro/organizations',
    '/titanpro/users',
    '/titanpro/subscriptions',
    '/titanpro/modules',
    '/titanpro/platform-health-dashboard',
]);

it('forbids non-super-admin access to TitanPro dedicated resources and health page', function (string $path) {
    $this->actingAs(titanProResourceUser('admin'))
        ->get($path)
        ->assertForbidden();
})->with([
    '/titanpro/organizations',
    '/titanpro/users',
    '/titanpro/subscriptions',
    '/titanpro/modules',
    '/titanpro/platform-health-dashboard',
]);
