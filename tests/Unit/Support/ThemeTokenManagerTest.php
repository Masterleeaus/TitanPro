<?php

use App\Models\PlatformSetting;
use App\Support\ThemeTokenManager;

it('defines primitive semantic and component token taxonomy with inherited component references', function () {
    $manager = new ThemeTokenManager();
    $settings = new PlatformSetting([
        'primary_color' => '#112233',
        'secondary_color' => '#223344',
        'accent_color' => '#334455',
        'surface_color' => '#f1f5f9',
        'font_heading' => 'Inter',
        'font_body' => 'Inter',
    ]);

    $tokens = $manager->load($settings);

    expect(array_keys($tokens))->toBe(['primitive', 'semantic', 'component'])
        ->and($tokens['component']['--btn-primary-bg'])->toBe('var(--color-primary)')
        ->and($tokens['component']['--card-bg'])->toBe('var(--color-surface)')
        ->and($manager->resolvedValues($tokens)['--btn-primary-bg'])->toBe('#112233')
        ->and($manager->resolvedValues($tokens)['--card-bg'])->toBe('#f1f5f9');
});

it('exports css style dictionary and tailwind token formats', function () {
    $manager = new ThemeTokenManager();
    $settings = new PlatformSetting([
        'primary_color' => '#445566',
        'secondary_color' => '#223344',
        'accent_color' => '#00aa88',
        'surface_color' => '#fafafa',
        'font_heading' => 'Inter',
        'font_body' => 'Inter',
    ]);

    $exports = $manager->exportPayload($settings);

    expect($exports['css'])->toContain('--color-primary: #445566;')
        ->and($exports['css'])->toContain('--btn-primary-bg: var(--color-primary);')
        ->and($exports['json'])->toHaveKey('semantic.color.primary.value')
        ->and($exports['json']['component']['btn']['primary']['bg']['value'])->toBe('{semantic.color.primary}')
        ->and($exports['tailwind'])->toContain('"var(--color-primary)"')
        ->and($exports['tailwind'])->toContain('fontFamily');
});
