<?php

use App\Models\Organization;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;

function zeroFussUser(string $role): User
{
    (new RolesAndPermissionsSeeder)->run();

    $org = Organization::factory()->create();
    $user = User::factory()->create(['organization_id' => $org->id]);
    $user->assignRole($role);

    return $user;
}

test('customer can access the ZeroFuss panel', function () {
    $this->actingAs(zeroFussUser('customer'))->get('/zerofuss')->assertOk();
});

test('owner cannot access the ZeroFuss panel', function () {
    $this->actingAs(zeroFussUser('owner'))->get('/zerofuss')->assertForbidden();
});

test('admin cannot access the ZeroFuss panel', function () {
    $this->actingAs(zeroFussUser('admin'))->get('/zerofuss')->assertForbidden();
});
