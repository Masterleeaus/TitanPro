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
