<?php

use App\Models\Organization;
use App\Models\UiOverride;
use App\Platform\Ui\ComponentRegistry;
use App\Services\UiOverrideCssRenderer;

// ── UiOverrideCssRenderer unit-style tests ────────────────────────────────────

test('render returns empty string when org has no overrides', function () {
    $org = Organization::factory()->create();

    expect(UiOverrideCssRenderer::render($org->id))->toBe('');
});

test('render generates CSS block using ComponentRegistry selector for known component', function () {
    $org = Organization::factory()->create();

    UiOverride::upsertForComponent(
        componentKey: 'stat-card',
        properties: ['background-color' => '#ff0000', 'border-radius' => '1rem'],
        organizationId: $org->id,
        userId: null,
    );

    $css = UiOverrideCssRenderer::render($org->id);

    $expectedSelector = ComponentRegistry::get('stat-card')['selector'];

    expect($css)
        ->toContain($expectedSelector)
        ->toContain('background-color: #ff0000')
        ->toContain('border-radius: 1rem');
});

test('render generates [data-ui-key] selector for non-registry component keys', function () {
    $org = Organization::factory()->create();

    UiOverride::upsertForComponent(
        componentKey: 'div.fi-wi[3]>div[0]',
        properties: ['padding' => '2rem'],
        organizationId: $org->id,
        userId: null,
    );

    $css = UiOverrideCssRenderer::render($org->id);

    expect($css)
        ->toContain('[data-ui-key="div.fi-wi[3]>div[0]"]')
        ->toContain('padding: 2rem');
});

test('render expands --gradient to background property', function () {
    $org = Organization::factory()->create();

    UiOverride::upsertForComponent(
        componentKey: 'stat-card',
        properties: ['--gradient' => 'linear-gradient(135deg,#6366f1,#8b5cf6)'],
        organizationId: $org->id,
        userId: null,
    );

    $css = UiOverrideCssRenderer::render($org->id);

    expect($css)->toContain('background: linear-gradient(135deg,#6366f1,#8b5cf6)');
});

test('render expands --glass to backdrop-filter and background-color', function () {
    $org = Organization::factory()->create();

    UiOverride::upsertForComponent(
        componentKey: 'modal',
        properties: ['--glass' => '8'],
        organizationId: $org->id,
        userId: null,
    );

    $css = UiOverrideCssRenderer::render($org->id);

    expect($css)
        ->toContain('backdrop-filter: blur(8px)')
        ->toContain('-webkit-backdrop-filter: blur(8px)')
        ->toContain('background-color: rgba(255,255,255,0.15)');
});

test('render expands --animation to transition property', function () {
    $org = Organization::factory()->create();

    UiOverride::upsertForComponent(
        componentKey: 'sidebar',
        properties: ['--animation' => 'fadeIn'],
        organizationId: $org->id,
        userId: null,
    );

    $css = UiOverrideCssRenderer::render($org->id);

    expect($css)->toContain('transition: opacity 0.4s ease');
});

test('render skips empty property values', function () {
    $org = Organization::factory()->create();

    UiOverride::upsertForComponent(
        componentKey: 'table',
        properties: ['background-color' => '', 'border-radius' => '0.5rem'],
        organizationId: $org->id,
        userId: null,
    );

    $css = UiOverrideCssRenderer::render($org->id);

    expect($css)
        ->not->toContain('background-color:')
        ->toContain('border-radius: 0.5rem');
});

test('render is scoped to org — overrides from another org are not included', function () {
    $orgA = Organization::factory()->create();
    $orgB = Organization::factory()->create();

    UiOverride::upsertForComponent(
        componentKey: 'stat-card',
        properties: ['background-color' => '#aabbcc'],
        organizationId: $orgA->id,
        userId: null,
    );

    $cssB = UiOverrideCssRenderer::render($orgB->id);

    expect($cssB)->toBe('');

    $cssA = UiOverrideCssRenderer::render($orgA->id);

    expect($cssA)->toContain('#aabbcc');
});

// ── buildDeclarations helper ──────────────────────────────────────────────────

test('buildDeclarations returns direct CSS property mapping for standard props', function () {
    $declarations = UiOverrideCssRenderer::buildDeclarations([
        'padding'       => '1.5rem',
        'border-radius' => '0.75rem',
        'color'         => '#111827',
    ]);

    expect($declarations)->toBe([
        'padding'       => '1.5rem',
        'border-radius' => '0.75rem',
        'color'         => '#111827',
    ]);
});
