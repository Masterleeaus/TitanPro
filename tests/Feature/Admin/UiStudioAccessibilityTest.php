<?php

use App\Models\PlatformSetting;
use App\Services\Accessibility\AccessibilityAudit;
use Database\Seeders\RolesAndPermissionsSeeder;
use App\Models\Organization;
use App\Models\TitanAccessibilityReport;
use App\Models\User;

function uiStudioOwner(): User
{
    (new RolesAndPermissionsSeeder)->run();

    $organization = Organization::factory()->create();
    $user = User::factory()->create(['organization_id' => $organization->id]);
    $user->assignRole('owner');

    return $user;
}

test('accessibility audit stores reports with failing checks', function () {
    $settings = PlatformSetting::current();
    $settings->update([
        'primary_color' => '#777777',
        'secondary_color' => '#8a8a8a',
        'background_color' => '#9a9a9a',
        'button_text_color' => '#8b8b8b',
        'focus_ring_color' => '#b0b0b0',
        'font_scale' => 0.8,
    ]);

    $service = app(AccessibilityAudit::class);
    $audit = $service->audit($settings->fresh());
    $report = $service->record($settings->fresh(), $audit);

    expect($report)->toBeInstanceOf(TitanAccessibilityReport::class)
        ->and(collect($audit['checks'])->firstWhere('key', 'text_background_contrast')['passed'])->toBeFalse()
        ->and(collect($audit['checks'])->firstWhere('key', 'button_text_contrast')['passed'])->toBeFalse()
        ->and($report->created_at)->not->toBeNull();
});

test('accessibility auto fix adjusts failing tokens to compliant values', function () {
    $settings = PlatformSetting::current();
    $settings->update([
        'primary_color' => '#777777',
        'secondary_color' => '#8a8a8a',
        'background_color' => '#9a9a9a',
        'button_text_color' => '#8b8b8b',
        'focus_ring_color' => '#b0b0b0',
        'font_scale' => 0.8,
    ]);

    $result = app(AccessibilityAudit::class)->autoFix($settings->fresh());

    expect($result['fixes'])->not->toBeEmpty()
        ->and(collect($result['audit']['checks'])->firstWhere('key', 'text_background_contrast')['passed'])->toBeTrue()
        ->and(collect($result['audit']['checks'])->firstWhere('key', 'button_text_contrast')['passed'])->toBeTrue()
        ->and(collect($result['audit']['checks'])->firstWhere('key', 'focus_ring_visibility')['passed'])->toBeTrue()
        ->and(collect($result['audit']['checks'])->firstWhere('key', 'font_size_minimum')['passed'])->toBeTrue();
});

test('owner can open the ui studio accessibility page', function () {
    PlatformSetting::current();

    $this->actingAs(uiStudioOwner())
        ->get('/admin/ui-studio')
        ->assertOk()
        ->assertSee('Accessibility')
        ->assertSee('Auto-fix failing tokens');
});
