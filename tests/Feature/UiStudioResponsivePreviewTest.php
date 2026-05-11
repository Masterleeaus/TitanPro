<?php

use App\Filament\Pages\UiStudio;

test('ui studio exposes required preview modes and viewports', function () {
    $studio = new UiStudio();

    expect($studio->previewModes())->toMatchArray([
        'desktop' => ['label' => 'Desktop', 'viewport' => 1440],
        'tablet' => ['label' => 'Tablet', 'viewport' => 1024],
        'mobile' => ['label' => 'Mobile', 'viewport' => 390],
        'collapsed' => ['label' => 'Collapsed sidebar', 'viewport' => 1440],
        'customer' => ['label' => 'Customer portal', 'viewport' => 390],
    ]);
});

test('ui studio preview viewport width follows selected mode', function () {
    $studio = new UiStudio();
    foreach ($studio->previewModes() as $mode => $config) {
        $studio->selectPreviewMode($mode);
        expect($studio->previewViewportWidth())->toBe($config['viewport']);
    }
});

test('ui studio can configure hidden table columns per breakpoint', function () {
    $studio = new UiStudio();
    $studio->canvasWidgets = [
        [
            'id' => 'w_table',
            'type' => 'table-card',
            'label' => 'Data Table',
            'columns' => 12,
            'order' => 0,
            'properties' => ['hidden_columns' => ['mobile' => [], 'tablet' => []]],
        ],
    ];

    $studio->updateTableColumnVisibility('w_table', 'mobile', 'owner', true);
    expect($studio->canvasWidgets[0]['properties']['hidden_columns']['mobile'])->toBe(['owner']);

    $studio->updateTableColumnVisibility('w_table', 'mobile', 'owner', false);
    expect($studio->canvasWidgets[0]['properties']['hidden_columns']['mobile'])->toBe([]);

    $studio->updateTableColumnVisibility('w_table', 'tablet', 'updated_at', true);

    expect($studio->canvasWidgets[0]['properties']['hidden_columns']['tablet'])->toBe(['updated_at']);
});
