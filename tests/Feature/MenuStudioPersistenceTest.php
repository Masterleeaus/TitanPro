<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

test('menu items are persisted and returned in navigation order', function () {
    $locationId = DB::table('fmm_menu_locations')->insertGetId([
        'handle' => 'primary',
        'name' => 'Primary',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $menuId = DB::table('fmm_menus')->insertGetId([
        'menu_location_id' => $locationId,
        'name' => 'Main Menu',
        'slug' => 'main-menu',
        'is_active' => true,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    DB::table('fmm_menu_items')->insert([
        [
            'menu_id' => $menuId,
            'parent_id' => null,
            'title' => 'Dashboard',
            'url' => '/admin',
            'icon' => 'heroicon-o-home',
            'target' => '_self',
            'type' => 'custom',
            'order' => 2,
            'enabled' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'menu_id' => $menuId,
            'parent_id' => null,
            'title' => 'Customers',
            'url' => '/admin/customers',
            'icon' => 'heroicon-o-users',
            'target' => '_self',
            'type' => 'custom',
            'order' => 1,
            'enabled' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ],
    ]);

    $navItems = DB::table('fmm_menu_items')
        ->where('menu_id', $menuId)
        ->where('enabled', true)
        ->orderBy('order')
        ->get(['title', 'url', 'icon', 'order']);

    expect($navItems->pluck('title')->all())->toBe(['Customers', 'Dashboard'])
        ->and($navItems->pluck('url')->all())->toBe(['/admin/customers', '/admin'])
        ->and($navItems->pluck('icon')->all())->toBe(['heroicon-o-users', 'heroicon-o-home']);
});

test('deleting a menu item removes it from navigation queries', function () {
    $locationId = DB::table('fmm_menu_locations')->insertGetId([
        'handle' => 'primary',
        'name' => 'Primary',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $menuId = DB::table('fmm_menus')->insertGetId([
        'menu_location_id' => $locationId,
        'name' => 'Main Menu',
        'slug' => 'main-menu',
        'is_active' => true,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $keptItemId = DB::table('fmm_menu_items')->insertGetId([
        'menu_id' => $menuId,
        'parent_id' => null,
        'title' => 'Dashboard',
        'url' => '/admin',
        'target' => '_self',
        'type' => 'custom',
        'order' => 1,
        'enabled' => true,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $deletedItemId = DB::table('fmm_menu_items')->insertGetId([
        'menu_id' => $menuId,
        'parent_id' => null,
        'title' => 'Deprecated Link',
        'url' => '/admin/deprecated',
        'target' => '_self',
        'type' => 'custom',
        'order' => 2,
        'enabled' => true,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    DB::table('fmm_menu_items')->where('id', $deletedItemId)->delete();

    $remainingIds = DB::table('fmm_menu_items')
        ->where('menu_id', $menuId)
        ->orderBy('order')
        ->pluck('id')
        ->all();

    expect($remainingIds)->toBe([$keptItemId]);
});

test('menu schema includes slug column for compatibility with unique index migration', function () {
    expect(Schema::hasColumn('fmm_menus', 'slug'))->toBeTrue();
});
