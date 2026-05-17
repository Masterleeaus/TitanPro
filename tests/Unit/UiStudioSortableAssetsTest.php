<?php

test('ui studio sortable assets are wired into the frontend pipeline', function () {
    $packageJson = file_get_contents(__DIR__.'/../../package.json');
    $viteConfig = file_get_contents(__DIR__.'/../../vite.config.ts');
    $bladeView = file_get_contents(__DIR__.'/../../resources/views/filament/pages/ui-studio.blade.php');
    $assetEntry = file_get_contents(__DIR__.'/../../resources/js/filament/ui-studio.js');

    expect($packageJson)
        ->toContain('"sortablejs": "^1.15.7"')
        ->and($viteConfig)->toContain("'resources/js/filament/ui-studio.js'")
        ->and($bladeView)->toContain("@vite('resources/js/filament/ui-studio.js')")
        ->and($bladeView)->toContain('titan-ui-studio:sortable-ready')
        ->and($bladeView)->toContain('wire.reorderWidgets(ids);')
        ->and($assetEntry)->toContain("import Sortable from 'sortablejs';")
        ->and($assetEntry)->toContain('window.Sortable = Sortable;');
});

test('ui studio source keeps reordered widgets on publish', function () {
    $pageClass = file_get_contents(__DIR__.'/../../app/Filament/Pages/UiStudio.php');

    expect($pageClass)
        ->toContain('public function reorderWidgets(array $orderedIds): void')
        ->toContain("\$widget['order'] = \$i;")
        ->toContain("'dashboard_layout' => \$this->canvasWidgets,")
        ->toContain("array_map(fn (\$w) => [")
        ->toContain("'type' => \$w['type'],")
        ->toContain("'title' => \$w['label']");
});
