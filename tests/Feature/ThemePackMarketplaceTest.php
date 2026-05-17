<?php

use App\Filament\Pages\UiStudio;
use App\Models\Organization;
use App\Models\ThemePack;
use App\Models\User;

it('saves current theme state as a custom theme pack', function (): void {
    $organization = Organization::factory()->create();
    $user = User::factory()->create(['organization_id' => $organization->id]);
    $this->actingAs($user);

    $studio = new UiStudio();
    $studio->mount();
    $studio->primaryColor = '#101010';
    $studio->secondaryColor = '#202020';
    $studio->accentColor = '#303030';
    $studio->surfaceColor = '#f1f1f1';
    $studio->fontHeading = 'Inter';
    $studio->fontBody = 'Inter';

    $studio->themePackName = 'Night Shift';
    $studio->themePackDescription = 'Dark preset for ops screens';
    $studio->themePackTags = 'dark, operations';
    $studio->themePackIsPublic = true;
    $studio->saveCurrentAsThemePack();

    $this->assertDatabaseHas('theme_packs', [
        'organization_id' => $organization->id,
        'name' => 'Night Shift',
        'slug' => 'night-shift',
        'is_public' => true,
    ]);

    $pack = ThemePack::query()->firstOrFail();

    expect($pack->tokens)
        ->toBeArray()
        ->and($pack->tokens['primary_color'] ?? null)->toBe('#101010')
        ->and($pack->tags)->toBe(['dark', 'operations']);
});

it('lists only current tenant theme packs', function (): void {
    $organizationA = Organization::factory()->create();
    $organizationB = Organization::factory()->create();

    $userA = User::factory()->create(['organization_id' => $organizationA->id]);
    $this->actingAs($userA);

    $packA = ThemePack::withoutGlobalScopes()->create([
        'organization_id' => $organizationA->id,
        'name' => 'Org A Pack',
        'slug' => 'org-a-pack',
        'description' => null,
        'tokens' => ['primary_color' => '#111111'],
        'preview_image_path' => null,
        'tags' => ['a'],
        'is_public' => false,
    ]);

    $packB = ThemePack::withoutGlobalScopes()->create([
        'organization_id' => $organizationB->id,
        'name' => 'Org B Pack',
        'slug' => 'org-b-pack',
        'description' => null,
        'tokens' => ['primary_color' => '#222222'],
        'preview_image_path' => null,
        'tags' => ['b'],
        'is_public' => false,
    ]);

    $studio = new UiStudio();
    $studio->mount();

    $ids = $studio->myThemePacks()->pluck('id')->all();

    expect($ids)
        ->toContain($packA->id)
        ->not->toContain($packB->id);
});

it('applies and deletes a theme pack for current tenant', function (): void {
    $organization = Organization::factory()->create();
    $user = User::factory()->create(['organization_id' => $organization->id]);
    $this->actingAs($user);

    $pack = ThemePack::withoutGlobalScopes()->create([
        'organization_id' => $organization->id,
        'name' => 'Apply Me',
        'slug' => 'apply-me',
        'description' => null,
        'tokens' => [
            'primary_color' => '#444444',
            'secondary_color' => '#555555',
            'accent_color' => '#666666',
            'surface_color' => '#fafafa',
            'font_heading' => 'Poppins',
            'font_body' => 'Poppins',
        ],
        'preview_image_path' => null,
        'tags' => [],
        'is_public' => false,
    ]);

    $studio = new UiStudio();
    $studio->mount();
    $studio->primaryColor = '#000000';

    $studio->applyThemePack($pack->id);

    expect($studio->primaryColor)->toBe('#444444')
        ->and($studio->fontHeading)->toBe('Poppins');

    $studio->deleteThemePack($pack->id);

    $this->assertDatabaseMissing('theme_packs', ['id' => $pack->id]);
});

it('prevents cross org apply and delete access through tenant scoping', function (): void {
    $organizationA = Organization::factory()->create();
    $organizationB = Organization::factory()->create();

    $userA = User::factory()->create(['organization_id' => $organizationA->id]);
    $this->actingAs($userA);

    $foreignPack = ThemePack::withoutGlobalScopes()->create([
        'organization_id' => $organizationB->id,
        'name' => 'Foreign',
        'slug' => 'foreign',
        'description' => null,
        'tokens' => ['primary_color' => '#abcdef'],
        'preview_image_path' => null,
        'tags' => [],
        'is_public' => false,
    ]);

    $studio = new UiStudio();
    $studio->mount();
    $startingColor = $studio->primaryColor;

    $studio->applyThemePack($foreignPack->id);
    $studio->deleteThemePack($foreignPack->id);

    expect($studio->primaryColor)->toBe($startingColor);
    $this->assertDatabaseHas('theme_packs', ['id' => $foreignPack->id]);
});
