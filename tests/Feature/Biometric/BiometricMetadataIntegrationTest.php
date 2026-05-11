<?php

use Filament\Contracts\Plugin;
use Modules\Biometric\Filament\Plugin\BiometricPlugin;

test('biometric module metadata is complete and targets titango panel', function () {
    $manifestPath = base_path('Modules/Biometric/module.json');

    $manifest = json_decode((string) file_get_contents($manifestPath), true, 512, JSON_THROW_ON_ERROR);

    expect($manifest['description'] ?? '')->not->toBe('')
        ->and($manifest['keywords'] ?? [])->toBeArray()->not->toBeEmpty()
        ->and($manifest['filament_panel'] ?? null)->toBe('titango')
        ->and($manifest['filament']['plugin'] ?? null)->toBe(BiometricPlugin::class);
});

test('biometric filament plugin is discoverable and follows filament plugin contract', function () {
    expect(class_exists(BiometricPlugin::class))->toBeTrue()
        ->and(is_a(BiometricPlugin::class, Plugin::class, true))->toBeTrue()
        ->and(BiometricPlugin::make()->getId())->toBe('biometric');
});

