<?php

test('zerofuss panel config is registered for customer portal use', function () {
    $panels = config('titan_panels.panels');

    expect($panels)->toHaveKey('zerofuss')
        ->and($panels['zerofuss']['path'])->toBe('zerofuss')
        ->and($panels['zerofuss']['label'])->toBe('ZeroFuss')
        ->and($panels['zerofuss']['roles'])->toBe(['customer']);
});

test('app sidebar includes zerofuss product switcher link', function () {
    $sidebar = file_get_contents(resource_path('js/components/AppSidebar.vue'));

    expect($sidebar)->toContain('/zerofuss')
        ->and($sidebar)->toContain('ZeroFuss Portal');
});
