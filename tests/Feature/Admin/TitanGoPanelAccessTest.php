<?php

use App\Models\Organization;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;

function titanGoUser(string $role): User
{
    (new RolesAndPermissionsSeeder)->run();

    $org = Organization::factory()->create();
    $user = User::factory()->create(['organization_id' => $org->id]);
    $user->assignRole($role);

    return $user;
}

test('owner can access the TitanGo panel', function () {
    $this->actingAs(titanGoUser('owner'))->get('/titango')->assertOk();
});

test('admin can access the TitanGo panel', function () {
    $this->actingAs(titanGoUser('admin'))->get('/titango')->assertOk();
});

test('super admin can access the TitanGo panel', function () {
    $this->actingAs(titanGoUser('super_admin'))->get('/titango')->assertOk();
});

test('technician cannot access the TitanGo panel', function () {
    $this->actingAs(titanGoUser('technician'))->get('/titango')->assertForbidden();
});

test('dispatcher cannot access the TitanGo panel', function () {
    $this->actingAs(titanGoUser('dispatcher'))->get('/titango')->assertForbidden();
});
