<?php

use App\Models\Organization;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;

beforeEach(function (): void {
    $this->seed(RolesAndPermissionsSeeder::class);
});

function uiStudioUser(string $role): User
{
    $organization = Organization::factory()->create();
    $user = User::factory()->create(['organization_id' => $organization->id]);
    $user->assignRole($role);

    return $user;
}

dataset('ui_studio_registered_panel_paths', [
    '/titanstudio/ui-studio',
    '/titannexus/ui-studio',
    '/titanquotes/ui-studio',
    '/groundzero/ui-studio',
    '/zeropay/ui-studio',
]);

test('owner can access ui studio in registered tenant panels', function (string $path): void {
    $this->actingAs(uiStudioUser('owner'))
        ->get($path)
        ->assertOk();
})->with('ui_studio_registered_panel_paths');

test('admin can access ui studio in registered tenant panels', function (string $path): void {
    $this->actingAs(uiStudioUser('admin'))
        ->get($path)
        ->assertOk();
})->with('ui_studio_registered_panel_paths');

test('dispatcher cannot access ui studio in titanquotes or groundzero', function (string $path): void {
    $this->actingAs(uiStudioUser('dispatcher'))
        ->get($path)
        ->assertForbidden();
})->with([
    '/titanquotes/ui-studio',
    '/groundzero/ui-studio',
]);

test('bookkeeper cannot access ui studio in groundzero or zeropay', function (string $path): void {
    $this->actingAs(uiStudioUser('bookkeeper'))
        ->get($path)
        ->assertForbidden();
})->with([
    '/groundzero/ui-studio',
    '/zeropay/ui-studio',
]);

test('ui studio is unavailable in non-registered panel routes', function (): void {
    $this->actingAs(uiStudioUser('owner'))
        ->get('/titango/ui-studio')
        ->assertNotFound();
});
