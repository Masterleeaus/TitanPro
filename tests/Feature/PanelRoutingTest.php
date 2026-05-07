<?php

use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;

dataset('canonical_panel_paths', [
    '/groundzero',
    '/titanquotes',
    '/zeropay',
    '/titango',
    '/zerofuss',
    '/titansolo',
    '/titanstudio',
    '/titannexus',
    '/titanpro',
]);

dataset('legacy_panel_aliases', [
    ['/ground-zero', '/groundzero'],
    ['/titan-go', '/titango'],
    ['/titan-quotes', '/titanquotes'],
    ['/titan-grow', '/titannexus'],
    ['/admin', '/titanpro'],
]);

test('canonical panel routes redirect guests to a login page', function (string $path) {
    $response = $this->get($path);

    expect($response->isRedirect())->toBeTrue();
    expect($response->headers->get('Location'))->toContain('login');
})->with('canonical_panel_paths');

test('canonical panel routes render for authenticated users', function (string $path) {
    (new RolesAndPermissionsSeeder())->run();

    $user = User::factory()->create();
    $user->assignRole('owner');

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
