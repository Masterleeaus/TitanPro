<?php

use App\Filament\Pages\UiStudio;
use App\Filament\Pages\UiStudio\WidgetPropertyRegistry;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// ── WidgetPropertyRegistry unit-level checks ─────────────────────────────────

test('WidgetPropertyRegistry returns a schema for every catalogue widget type', function () {
    $catalogueTypes = [
        'html-card',
        'stat-card',
        'kpi-grid-card',
        'alert-notice-card',
        'recent-activity-card',
        'cta-button-card',
        'chart-bar-card',
        'chart-line-card',
        'table-card',
        'map-card',
    ];

    foreach ($catalogueTypes as $type) {
        $schema = WidgetPropertyRegistry::schema($type);
        expect($schema)
            ->toBeArray()
            ->not->toBeEmpty("Expected non-empty schema for widget type '{$type}'");
    }
});

test('WidgetPropertyRegistry schema fields have required keys', function () {
    foreach (WidgetPropertyRegistry::all() as $type => $fields) {
        foreach ($fields as $field) {
            expect($field)
                ->toHaveKey('key',   "Widget '{$type}' field missing 'key'")
                ->toHaveKey('label', "Widget '{$type}' field missing 'label'")
                ->toHaveKey('type',  "Widget '{$type}' field missing 'type'")
                ->toHaveKey('default', "Widget '{$type}' field missing 'default'");
        }
    }
});

test('WidgetPropertyRegistry defaults returns a flat key-value map', function () {
    $defaults = WidgetPropertyRegistry::defaults('stat-card');

    expect($defaults)
        ->toHaveKey('title')
        ->toHaveKey('value')
        ->toHaveKey('color')
        ->and($defaults['color'])->toBe('primary');
});

test('WidgetPropertyRegistry returns empty array for unknown widget type', function () {
    expect(WidgetPropertyRegistry::schema('totally-unknown-widget'))->toBe([]);
    expect(WidgetPropertyRegistry::defaults('totally-unknown-widget'))->toBe([]);
});

// ── UiStudio Livewire integration ─────────────────────────────────────────────

test('updateWidgetProperty writes value into canvasWidgets properties', function () {
    $org  = Organization::factory()->create();
    $user = User::factory()->create(['organization_id' => $org->id]);
    $this->actingAs($user);

    $studio = new UiStudio();
    $studio->mount();

    // Add a stat-card widget.
    $studio->addWidget('stat-card');

    $widgetId = $studio->canvasWidgets[0]['id'];

    // Select it so property editing is activated.
    $studio->selectWidget($widgetId);

    // Update a property.
    $studio->updateWidgetProperty('title', 'Monthly Revenue');

    // The in-memory editor state should reflect the change.
    expect($studio->widgetPropertyValues['title'])->toBe('Monthly Revenue');

    // The canvas widget properties array should also be updated.
    $found = collect($studio->canvasWidgets)->firstWhere('id', $widgetId);
    expect($found['properties']['title'])->toBe('Monthly Revenue');
});

test('publish persists widget properties in layouts table', function () {
    if (! Schema::hasTable('layouts')) {
        $this->markTestSkipped('layouts table not present');
    }

    $org  = Organization::factory()->create();
    $user = User::factory()->create(['organization_id' => $org->id]);
    $this->actingAs($user);

    $studio = new UiStudio();
    $studio->mount();

    // Add a stat-card and configure its properties.
    $studio->addWidget('stat-card');
    $widgetId = $studio->canvasWidgets[0]['id'];
    $studio->selectWidget($widgetId);
    $studio->updateWidgetProperty('title', 'Total Jobs');
    $studio->updateWidgetProperty('value', '42');
    $studio->updateWidgetProperty('color', 'success');

    // Publish — we call the underlying method directly because the Action
    // wrapper requires HTTP context for validation redirects.
    // Inject valid hex colours to pass publish() validation.
    $studio->primaryColor   = '#2563eb';
    $studio->secondaryColor = '#0f172a';
    $studio->backgroundType = 'none';
    $studio->publish();

    // Retrieve the persisted layout row.
    $row     = DB::table('layouts')->where('layout_slug', 'ui-studio-layout')->first();
    $widgets = json_decode($row->widgets, true);

    expect($widgets)->toBeArray()->not->toBeEmpty();

    $saved = $widgets[0];
    expect($saved['type'])->toBe('stat-card')
        ->and($saved['data']['title'])->toBe('Total Jobs')
        ->and($saved['data']['value'])->toBe('42')
        ->and($saved['data']['color'])->toBe('success');
});

test('loadCanvasWidgets restores widget properties from layouts table', function () {
    if (! Schema::hasTable('layouts')) {
        $this->markTestSkipped('layouts table not present');
    }

    $org  = Organization::factory()->create();
    $user = User::factory()->create(['organization_id' => $org->id]);
    $this->actingAs($user);

    // Pre-seed a layout row with saved property data.
    DB::table('layouts')->updateOrInsert(
        ['layout_slug' => 'ui-studio-layout'],
        [
            'user_id'       => $user->id,
            'layout_title'  => 'Test Layout',
            'layout_slug'   => 'ui-studio-layout',
            'widgets'       => json_encode([[
                'type' => 'stat-card',
                'data' => ['title' => 'Saved Title', 'value' => '99', 'color' => 'danger'],
            ]]),
            'is_active'     => 1,
            'created_at'    => now(),
            'updated_at'    => now(),
        ]
    );

    $studio = new UiStudio();
    $studio->mount();

    expect($studio->canvasWidgets)->toHaveCount(1);

    $widget = $studio->canvasWidgets[0];
    expect($widget['type'])->toBe('stat-card')
        ->and($widget['label'])->toBe('Saved Title')
        ->and($widget['properties']['value'])->toBe('99')
        ->and($widget['properties']['color'])->toBe('danger');
});
