<?php

use App\Platform\Ui\ComponentRegistry;

it('declares stable data-ui-key selectors for all registered component keys', function () {
    $source = file_get_contents(base_path('public/js/titan/ui-inspector.js'));

    expect($source)->not->toBeFalse();

    foreach (array_keys(ComponentRegistry::all()) as $componentKey) {
        expect($source)->toContain("key: '{$componentKey}'");
    }

    expect($source)
        ->toContain('function assignStableUiKeys(root = document)')
        ->toContain('el.dataset.uiKey = key');
});

it('restores selected component lookup by data-ui-key after livewire navigation', function () {
    $source = file_get_contents(base_path('public/js/titan/ui-inspector.js'));

    expect($source)->not->toBeFalse();

    expect($source)
        ->toContain("const keyedMatch = el.closest('[data-ui-key]');")
        ->toContain('const selected = findComponentByKey(this.selectedKey);')
        ->toContain("document.addEventListener('livewire:navigated', this._onLivewire);");
});
