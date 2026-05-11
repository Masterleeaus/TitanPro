<?php

// Tests that TitanGo product launcher widgets link to /titango, not /technician/dashboard.

test('pwa launch widget links to /titango not /technician/dashboard', function () {
    $blade = file_get_contents(resource_path('views/filament/widgets/pwa-launch-widget.blade.php'));

    expect($blade)->toContain('/titango')
        ->and($blade)->not->toContain('/technician/dashboard');
});

test('cleaning operations overview widget links to /titango not /technician/dashboard', function () {
    $blade = file_get_contents(resource_path('views/filament/widgets/cleaning-operations-overview.blade.php'));

    expect($blade)->toContain('/titango')
        ->and($blade)->not->toContain('/technician/dashboard');
});

test('cleaner live map widget links to /titango not /technician/dashboard', function () {
    $blade = file_get_contents(resource_path('views/filament/widgets/cleaner-live-map.blade.php'));

    expect($blade)->toContain('/titango')
        ->and($blade)->not->toContain('/technician/dashboard');
});

test('titango panel config path is titango', function () {
    $panels = config('titan_panels.panels');

    expect($panels)->toHaveKey('titango')
        ->and($panels['titango']['path'])->toBe('titango')
        ->and($panels['titango']['label'])->toBe('TitanGo');
});
