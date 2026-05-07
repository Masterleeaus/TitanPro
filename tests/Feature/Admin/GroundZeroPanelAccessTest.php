<?php

use App\Models\Organization;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;

function groundZeroUser(string $role): User
{
    (new RolesAndPermissionsSeeder)->run();

    $org  = Organization::factory()->create();
    $user = User::factory()->create(['organization_id' => $org->id]);
    $user->assignRole($role);

    return $user;
}

test('owner can access the GroundZero panel', function () {
    $this->actingAs(groundZeroUser('owner'))->get('/groundzero')->assertOk();
});

test('admin can access the GroundZero panel', function () {
    $this->actingAs(groundZeroUser('admin'))->get('/groundzero')->assertOk();
});

test('dispatcher can access the GroundZero panel', function () {
    $this->actingAs(groundZeroUser('dispatcher'))->get('/groundzero')->assertOk();
});

test('bookkeeper can access the GroundZero panel', function () {
    $this->actingAs(groundZeroUser('bookkeeper'))->get('/groundzero')->assertOk();
});

test('technician cannot access the GroundZero panel', function () {
    $this->actingAs(groundZeroUser('technician'))->get('/groundzero')->assertForbidden();
});

test('owner user lands on the GroundZero dashboard after login', function () {
    $this->actingAs(groundZeroUser('owner'))
        ->followingRedirects()
        ->get('/groundzero')
        ->assertOk();
});
