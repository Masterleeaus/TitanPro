<?php

use App\Models\Organization;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;

test('customer can access the ZeroFuss panel', function () {
    (new RolesAndPermissionsSeeder)->run();

    $org = Organization::factory()->create();
    $user = User::factory()->create(['organization_id' => $org->id]);
    $user->assignRole('customer');

    $this->actingAs($user)->get('/zerofuss')->assertOk();
});

test('owner cannot access the ZeroFuss panel', function () {
    (new RolesAndPermissionsSeeder)->run();

    $org = Organization::factory()->create();
    $user = User::factory()->create(['organization_id' => $org->id]);
    $user->assignRole('owner');

    $this->actingAs($user)->get('/zerofuss')->assertForbidden();
});

test('admin cannot access the ZeroFuss panel', function () {
    (new RolesAndPermissionsSeeder)->run();

    $org = Organization::factory()->create();
    $user = User::factory()->create(['organization_id' => $org->id]);
    $user->assignRole('admin');

    $this->actingAs($user)->get('/zerofuss')->assertForbidden();
});
