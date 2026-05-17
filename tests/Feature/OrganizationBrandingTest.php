<?php

use App\Models\Organization;
use App\Models\OrganizationBranding;
use App\Models\User;
use App\Support\OrganizationBrandingResolver;

test('organization branding model persists required white-label fields', function () {
    $organization = Organization::factory()->create();

    $branding = OrganizationBranding::create([
        'organization_id' => $organization->id,
        'logo_path' => 'organization-branding/logo.png',
        'favicon_path' => 'organization-branding/favicon.png',
        'primary_color' => '#112233',
        'secondary_color' => '#445566',
        'font_family' => 'Inter',
        'background_type' => 'gradient',
        'background_value' => 'linear-gradient(45deg,#111,#222)',
        'panel_name' => 'Acme Control',
    ]);

    expect($branding->organization_id)->toBe($organization->id)
        ->and($branding->panel_name)->toBe('Acme Control')
        ->and($branding->primary_color)->toBe('#112233')
        ->and($branding->secondary_color)->toBe('#445566')
        ->and($branding->font_family)->toBe('Inter')
        ->and($branding->background_type)->toBe('gradient');
});

test('branding resolver falls back to platform defaults when organization branding is missing', function () {
    $organization = Organization::factory()->create();
    $user = User::factory()->create(['organization_id' => $organization->id]);

    $this->actingAs($user);

    $branding = app(OrganizationBrandingResolver::class)->current();

    expect($branding['panel_name'])->not->toBe('')
        ->and($branding['primary_color'])->toStartWith('#');
});

test('branding resolver returns organization overrides when configured', function () {
    $organization = Organization::factory()->create();
    $user = User::factory()->create(['organization_id' => $organization->id]);

    OrganizationBranding::create([
        'organization_id' => $organization->id,
        'panel_name' => 'Branded Panel',
        'primary_color' => '#aabbcc',
        'secondary_color' => '#ddeeff',
        'font_family' => 'Poppins',
        'background_type' => 'gradient',
        'background_value' => 'linear-gradient(#000,#fff)',
    ]);

    $this->actingAs($user);

    $branding = app(OrganizationBrandingResolver::class)->current();

    expect($branding['panel_name'])->toBe('Branded Panel')
        ->and($branding['primary_color'])->toBe('#aabbcc')
        ->and($branding['secondary_color'])->toBe('#ddeeff')
        ->and($branding['font_family'])->toBe('Poppins')
        ->and($branding['background_type'])->toBe('gradient');
});

test('installing a theme pack adds it to organization branding library', function () {
    $organization = Organization::factory()->create();
    $branding = OrganizationBranding::create(['organization_id' => $organization->id]);

    $branding->installThemePack([
        'slug' => 'my-private-pack',
        'name' => 'My Private Pack',
        'author' => 'Acme',
        'version' => '1.2.3',
        'tags' => ['private', 'ops'],
        'tokens' => [
            'primary_color' => '#123456',
            'secondary_color' => '#654321',
        ],
    ]);

    $branding->refresh();
    $installed = $branding->installedThemePacks();

    expect($installed)->toHaveCount(1)
        ->and($installed[0]['slug'])->toBe('my-private-pack')
        ->and($installed[0]['name'])->toBe('My Private Pack')
        ->and($installed[0]['tokens']['primary_color'])->toBe('#123456');
});

test('uninstalling a theme pack removes it from organization branding library', function () {
    $organization = Organization::factory()->create();
    $branding = OrganizationBranding::create(['organization_id' => $organization->id]);

    $branding->installThemePack(['slug' => 'pack-one', 'name' => 'Pack One', 'tokens' => ['primary_color' => '#111111']]);
    $branding->installThemePack(['slug' => 'pack-two', 'name' => 'Pack Two', 'tokens' => ['primary_color' => '#222222']]);
    $branding->uninstallThemePack('pack-one');

    $branding->refresh();
    $installed = $branding->installedThemePacks();

    expect($installed)->toHaveCount(1)
        ->and($installed[0]['slug'])->toBe('pack-two');
});

test('installed theme pack libraries are isolated per organization', function () {
    $organizationA = Organization::factory()->create();
    $organizationB = Organization::factory()->create();
    $userA = User::factory()->create(['organization_id' => $organizationA->id]);
    $userB = User::factory()->create(['organization_id' => $organizationB->id]);

    $brandingA = OrganizationBranding::withoutGlobalScopes()->create(['organization_id' => $organizationA->id]);
    $brandingB = OrganizationBranding::withoutGlobalScopes()->create(['organization_id' => $organizationB->id]);

    $brandingA->installThemePack(['slug' => 'org-a-pack', 'name' => 'Org A Pack', 'tokens' => ['primary_color' => '#aa0000']]);
    $brandingB->installThemePack(['slug' => 'org-b-pack', 'name' => 'Org B Pack', 'tokens' => ['primary_color' => '#00aa00']]);

    $this->actingAs($userA);
    $visibleA = OrganizationBranding::first();
    expect($visibleA)->not->toBeNull()
        ->and($visibleA?->organization_id)->toBe($organizationA->id)
        ->and($visibleA?->installedThemePacks())->toHaveCount(1)
        ->and($visibleA?->installedThemePacks()[0]['slug'])->toBe('org-a-pack');

    $this->actingAs($userB);
    $visibleB = OrganizationBranding::first();
    expect($visibleB)->not->toBeNull()
        ->and($visibleB?->organization_id)->toBe($organizationB->id)
        ->and($visibleB?->installedThemePacks())->toHaveCount(1)
        ->and($visibleB?->installedThemePacks()[0]['slug'])->toBe('org-b-pack');
});
