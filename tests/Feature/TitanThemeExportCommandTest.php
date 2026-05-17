<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

it('exports all supported UI theme formats from titan export command', function () {
    $directory = storage_path('framework/testing/titan-theme-export-'.Str::random(8));

    $formats = [
        'theme_zip' => 'zip',
        'ui_pack' => 'zip',
        'branding_kit' => 'zip',
        'tenant_preset' => 'zip',
        'css' => 'css',
        'style_dictionary' => 'json',
    ];

    foreach ($formats as $format => $extension) {
        $exitCode = Artisan::call('titan:export:theme', [
            'name' => 'Acme Ops',
            'format' => $format,
            '--path' => $directory,
        ]);

        expect($exitCode)->toBe(0);

        $outputFile = $directory.'/acme-ops-'.$format.'.'.$extension;
        expect(file_exists($outputFile))->toBeTrue();

        if ($format === 'css') {
            expect(file_get_contents($outputFile))->toContain(':root');
        }

        if ($format === 'style_dictionary') {
            expect(file_get_contents($outputFile))->toContain('semantic');
        }
    }
});

it('includes required files in exported package zip formats', function () {
    $directory = storage_path('framework/testing/titan-theme-export-'.Str::random(8));

    Artisan::call('titan:export:theme', [
        'name' => 'Acme Ops',
        'format' => 'theme_zip',
        '--path' => $directory,
    ]);

    Artisan::call('titan:export:theme', [
        'name' => 'Acme Ops',
        'format' => 'ui_pack',
        '--path' => $directory,
    ]);

    Artisan::call('titan:export:theme', [
        'name' => 'Acme Ops',
        'format' => 'branding_kit',
        '--path' => $directory,
    ]);

    Artisan::call('titan:export:theme', [
        'name' => 'Acme Ops',
        'format' => 'tenant_preset',
        '--path' => $directory,
    ]);

    $themeZip = new \ZipArchive();
    $themeZip->open($directory.'/acme-ops-theme_zip.zip');
    expect($themeZip->locateName('theme.json'))->not->toBeFalse()
        ->and($themeZip->locateName('meta.json'))->not->toBeFalse()
        ->and($themeZip->locateName('preview.png'))->not->toBeFalse();
    $themeZip->close();

    $uiPack = new \ZipArchive();
    $uiPack->open($directory.'/acme-ops-ui_pack.zip');
    expect($uiPack->locateName('theme.json'))->not->toBeFalse()
        ->and($uiPack->locateName('component-overrides.json'))->not->toBeFalse()
        ->and($uiPack->locateName('dashboard-layout.json'))->not->toBeFalse();
    $uiPack->close();

    $brandingKit = new \ZipArchive();
    $brandingKit->open($directory.'/acme-ops-branding_kit.zip');
    expect($brandingKit->locateName('branding.json'))->not->toBeFalse()
        ->and($brandingKit->locateName('brand-colors.json'))->not->toBeFalse()
        ->and($brandingKit->locateName('fonts.json'))->not->toBeFalse();
    $brandingKit->close();

    $tenantPreset = new \ZipArchive();
    $tenantPreset->open($directory.'/acme-ops-tenant_preset.zip');
    expect($tenantPreset->locateName('tenant-preset.json'))->not->toBeFalse();
    $tenantPreset->close();
});
