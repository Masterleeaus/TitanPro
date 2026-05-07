<?php

use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;

dataset('canonical_panel_paths', [
    ['/groundzero', 'owner'],
    ['/titanquotes', 'owner'],
    ['/zeropay', 'owner'],
    ['/titango', 'owner'],
    ['/zerofuss', 'owner'],
    ['/titansolo', 'owner'],
    ['/titanstudio', 'owner'],
    ['/titannexus', 'owner'],
    ['/titanpro', 'super_admin'],
]);

dataset('legacy_panel_aliases', [
    ['/ground-zero', '/groundzero'],
    ['/titan-go', '/titango'],
    ['/titan-quotes', '/titanquotes'],
    ['/titan-grow', '/titannexus'],
    ['/admin', '/titanpro'],
]);

test('canonical panel routes redirect guests to a login page', function (string $path, string $_role) {
    $response = $this->get($path);
    $location = $response->headers->get('Location');

    $response->assertStatus(302);
    expect($response->isRedirect())->toBeTrue();
    expect(parse_url((string) $location, PHP_URL_PATH))->toEndWith('/login');
})->with('canonical_panel_paths');

test('canonical panel routes render for authenticated users', function (string $path, string $role) {
    $this->seed(RolesAndPermissionsSeeder::class);

    $user = User::factory()->create();
    $user->assignRole($role);

    $this->actingAs($user)
        ->followingRedirects()
        ->get($path)
        ->assertOk();
})->with('canonical_panel_paths');

test('legacy panel aliases permanently redirect to canonical routes', function (string $alias, string $canonical) {
    $response = $this->get($alias);

    $response->assertStatus(301);
    $response->assertRedirect($canonical);
})->with('legacy_panel_aliases');
