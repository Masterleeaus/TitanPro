<?php

use App\Platform\Ui\ComponentRegistry;
use Illuminate\Support\Facades\File;

beforeEach(function () {
    expect(File::exists(base_path('public/js/titan/ui-inspector.js')))->toBeTrue();
    $this->source = file_get_contents(base_path('public/js/titan/ui-inspector.js'));
});

it('declares stable data-ui-key selectors for all registered component keys', function () {
    expect($this->source)->not->toBeFalse();

    foreach (array_keys(ComponentRegistry::all()) as $componentKey) {
        expect($this->source)->toContain("key: '{$componentKey}'");
    }

    expect($this->source)
        ->toContain('function assignStableUiKeys(root = document)')
        ->toContain('el.dataset.uiKey = key');
});

it('restores selected component lookup by data-ui-key after livewire navigation', function () {
    expect($this->source)->not->toBeFalse();

    expect($this->source)
        ->toContain("const keyedMatch = el.closest('[data-ui-key]');")
        ->toContain('const selected = findComponentByKey(this.selectedKey);')
        ->toContain("document.addEventListener('livewire:navigated', this._onLivewire);");
});
