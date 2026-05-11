<?php

use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;

test('titanstudio panel config uses expected id and path', function () {
    $panels = config('titan_panels.panels');

    expect($panels)->toHaveKey('titanstudio')
        ->and($panels['titanstudio']['path'])->toBe('titanstudio')
        ->and($panels['titanstudio']['label'])->toBe('TitanStudio')
        ->and($panels['titanstudio']['roles'])->toBe(['owner', 'admin']);
});

test('owner can access titanstudio create pages for message templates and service checklists', function () {
    $this->seed(RolesAndPermissionsSeeder::class);

    $user = User::factory()->create();
    $user->assignRole('owner');

    $this->actingAs($user)->get('/titanstudio/message-templates/create')->assertOk();
    $this->actingAs($user)->get('/titanstudio/job-type-checklist-items/create')->assertOk();
});

test('dispatcher cannot access titanstudio panel', function () {
    $this->seed(RolesAndPermissionsSeeder::class);

    $user = User::factory()->create();
    $user->assignRole('dispatcher');

    $this->actingAs($user)->get('/titanstudio')->assertForbidden();
});
