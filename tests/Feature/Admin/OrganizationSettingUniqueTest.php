<?php

use App\Filament\Resources\OrganizationSettingResource;
use App\Models\Organization;
use App\Models\OrganizationSetting;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;

/**
 * Verifies that the OrganizationSetting unique-per-org constraint and the
 * create-page redirect guard work correctly.
 */
beforeEach(function () {
    (new RolesAndPermissionsSeeder)->run();
});

test('firstOrCreateForOrganization returns existing row without creating a duplicate', function () {
    $org = Organization::factory()->create();

    $first  = OrganizationSetting::firstOrCreateForOrganization($org->id);
    $second = OrganizationSetting::firstOrCreateForOrganization($org->id);

    expect($second->id)->toBe($first->id);
    expect(OrganizationSetting::where('organization_id', $org->id)->count())->toBe(1);
});

test('firstOrCreateForOrganization creates a row when none exists', function () {
    $org = Organization::factory()->create();

    expect(OrganizationSetting::where('organization_id', $org->id)->exists())->toBeFalse();

    $setting = OrganizationSetting::firstOrCreateForOrganization($org->id);

    expect($setting->organization_id)->toBe($org->id);
    expect(OrganizationSetting::where('organization_id', $org->id)->count())->toBe(1);
});

test('create page redirects to edit when org already has a settings row', function () {
    $org     = Organization::factory()->create();
    $user    = User::factory()->create(['organization_id' => $org->id]);
    $user->assignRole('owner');

    $existing = OrganizationSetting::factory()->create(['organization_id' => $org->id]);

    $expectedUrl = OrganizationSettingResource::getUrl('edit', ['record' => $existing]);

    $this->actingAs($user)
        ->get('/admin/organization-settings/create')
        ->assertRedirect($expectedUrl);
});

test('create page renders normally when org has no settings row', function () {
    $org  = Organization::factory()->create();
    $user = User::factory()->create(['organization_id' => $org->id]);
    $user->assignRole('owner');

    $this->actingAs($user)
        ->get('/admin/organization-settings/create')
        ->assertOk();
});
