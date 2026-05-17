<?php

use Modules\TitanZero\Services\WidgetFactory;

test('widget factory maps metric intent to metric-card widget', function () {
    $widget = app(WidgetFactory::class)->makeFromIntent('Show revenue today', 'Revenue is stable today.');

    expect($widget)
        ->toHaveKeys(['id', 'kind', 'title', 'data'])
        ->and($widget['kind'])->toBe('metric-card')
        ->and($widget['data'])->toBeArray();
});

test('widget factory maps list intent to data-table widget', function () {
    $widget = app(WidgetFactory::class)->makeFromIntent('List overdue invoices', 'Here are the overdue invoices.');

    expect($widget)
        ->toHaveKeys(['id', 'kind', 'title', 'data'])
        ->and($widget['kind'])->toBe('data-table')
        ->and($widget['data'])->toBeArray();
});
