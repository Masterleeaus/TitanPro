<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// ---------------------------------------------------------------------------
// UI Studio — menu persistence via fmm_* tables
// ---------------------------------------------------------------------------
// These tests exercise the raw SQL paths that UiStudio::loadMenuItems() and
// UiStudio::persistMenuItemsToFmm() depend on.  They intentionally avoid
// booting the full Livewire page so they stay fast and framework-agnostic.
// ---------------------------------------------------------------------------

// ── Shared helpers (fmm = FilamentMenuManager — the fmm_* table prefix) ────

/**
 * Seed a fmm_menu_locations row and return its ID.
 */
function fmmSeedLocation(string $handle): int
{
    $now = now();

    return DB::table('fmm_menu_locations')->insertGetId([
        'handle'     => $handle,
        'name'       => 'UI Studio',
        'created_at' => $now,
        'updated_at' => $now,
    ]);
}

/**
 * Seed a fmm_menus row for the given location and return its ID.
 */
function fmmSeedMenu(int $locationId, string $slug, int $orgId): int
{
    $now = now();

    return DB::table('fmm_menus')->insertGetId([
        'menu_location_id' => $locationId,
        'name'             => 'UI Studio – Org ' . $orgId,
        'slug'             => $slug,
        'is_active'        => true,
        'created_at'       => $now,
        'updated_at'       => $now,
    ]);
}

/**
 * Insert an array of menu item definitions into fmm_menu_items.
 * Each entry: ['label', 'url', 'icon', 'order', 'enabled' (optional)].
 *
 * Mirrors the insert structure used by persistMenuItemsToFmm().
 */
function fmmSeedItems(int $menuId, array $items): void
{
    $now = now();

    foreach ($items as $item) {
        DB::table('fmm_menu_items')->insert([
            'menu_id'    => $menuId,
            'parent_id'  => null,
            'title'      => $item['label'],
            'url'        => $item['url'] ?? '/',
            'icon'       => $item['icon'] ?? null,
            'target'     => '_self',
            'type'       => 'custom',
            'order'      => $item['order'] ?? 0,
            'enabled'    => $item['enabled'] ?? true,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }
}

/**
 * Fetch ordered, enabled menu items from fmm_menu_items.
 * Returns an array shaped like UiStudio::loadMenuItems() output.
 *
 * @return array<int, array{id: string, label: string, url: string, icon: string, order: int}>
 */
function fmmLoadItems(int $menuId): array
{
    return DB::table('fmm_menu_items')
        ->where('menu_id', $menuId)
        ->where('enabled', true)
        ->orderBy('order')
        ->get(['id', 'title', 'url', 'icon', 'order'])
        ->values()
        ->map(fn ($row) => [
            'id'    => 'm_' . $row->id,
            'label' => $row->title,
            'url'   => $row->url ?? '/',
            'icon'  => $row->icon ?? 'heroicon-o-link',
            'order' => (int) $row->order,
        ])
        ->all();
}

// ── Tests ───────────────────────────────────────────────────────────────────

test('fmm tables exist and accept menu data', function () {
    expect(Schema::hasTable('fmm_menu_locations'))->toBeTrue()
        ->and(Schema::hasTable('fmm_menus'))->toBeTrue()
        ->and(Schema::hasTable('fmm_menu_items'))->toBeTrue();
});

test('ui-studio location and per-org menu can be seeded and retrieved', function () {
    $locationId = fmmSeedLocation('ui-studio-test');
    $menuId     = fmmSeedMenu($locationId, 'ui-studio-org-999', 999);

    // Insert items in reverse order so ORDER BY is exercised.
    fmmSeedItems($menuId, [
        ['label' => 'Invoices',  'url' => '/titanpro/invoices', 'icon' => 'heroicon-o-document-text', 'order' => 1],
        ['label' => 'Dashboard', 'url' => '/titanpro',          'icon' => 'heroicon-o-home',          'order' => 0],
    ]);

    $items = fmmLoadItems($menuId);

    expect($items)->toHaveCount(2)
        ->and($items[0]['label'])->toBe('Dashboard')
        ->and($items[0]['order'])->toBe(0)
        ->and($items[1]['label'])->toBe('Invoices')
        ->and($items[1]['order'])->toBe(1);
});

test('replacing menu items deletes old rows and inserts new ones', function () {
    $locationId = fmmSeedLocation('ui-studio-replace-test');
    $menuId     = fmmSeedMenu($locationId, 'ui-studio-org-998', 998);

    // Seed an old item.
    fmmSeedItems($menuId, [
        ['label' => 'Old Page', 'url' => '/old', 'order' => 0],
    ]);

    // Simulate persistMenuItemsToFmm: delete + re-insert.
    DB::table('fmm_menu_items')->where('menu_id', $menuId)->delete();

    fmmSeedItems($menuId, [
        ['label' => 'Dashboard', 'url' => '/titanpro',           'icon' => 'heroicon-o-home',  'order' => 0],
        ['label' => 'Customers', 'url' => '/titanpro/customers', 'icon' => 'heroicon-o-users', 'order' => 1],
    ]);

    $titles = DB::table('fmm_menu_items')
        ->where('menu_id', $menuId)
        ->orderBy('order')
        ->pluck('title')
        ->all();

    expect($titles)->toBe(['Dashboard', 'Customers'])
        ->and(DB::table('fmm_menu_items')->where('title', 'Old Page')->exists())->toBeFalse();
});

test('disabled menu items are excluded from navigation queries', function () {
    $locationId = fmmSeedLocation('ui-studio-disabled-test');
    $menuId     = fmmSeedMenu($locationId, 'ui-studio-org-997', 997);

    fmmSeedItems($menuId, [
        ['label' => 'Visible', 'url' => '/visible', 'order' => 0, 'enabled' => true],
        ['label' => 'Hidden',  'url' => '/hidden',  'order' => 1, 'enabled' => false],
    ]);

    $titles = DB::table('fmm_menu_items')
        ->where('menu_id', $menuId)
        ->where('enabled', true)
        ->orderBy('order')
        ->pluck('title')
        ->all();

    expect($titles)->toBe(['Visible']);
});

test('per-org menu slug is isolated — different orgs have separate rows', function () {
    $locationId = fmmSeedLocation('ui-studio-isolation-test');

    foreach ([100, 200] as $orgId) {
        $menuId = fmmSeedMenu($locationId, 'ui-studio-org-' . $orgId, $orgId);
        fmmSeedItems($menuId, [
            ['label' => 'Org ' . $orgId . ' Home', 'url' => '/org-' . $orgId, 'order' => 0],
        ]);
    }

    // Each slug returns only its own item.
    foreach ([100, 200] as $orgId) {
        $menu   = DB::table('fmm_menus')->where('slug', 'ui-studio-org-' . $orgId)->first();
        $titles = DB::table('fmm_menu_items')
            ->where('menu_id', $menu->id)
            ->pluck('title')
            ->all();

        expect($titles)->toBe(['Org ' . $orgId . ' Home']);
    }
});
