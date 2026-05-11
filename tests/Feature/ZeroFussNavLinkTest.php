<?php

test('ZeroFuss panel configuration contains expected customer portal settings', function () {
    $panels = config('titan_panels.panels');

    expect($panels)->toHaveKey('zerofuss')
        ->and($panels['zerofuss']['path'])->toBe('zerofuss')
        ->and($panels['zerofuss']['label'])->toBe('ZeroFuss')
        ->and($panels['zerofuss']['roles'])->toBe(['customer']);
});
