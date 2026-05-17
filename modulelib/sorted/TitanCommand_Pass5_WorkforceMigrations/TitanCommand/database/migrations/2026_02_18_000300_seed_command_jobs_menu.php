<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tables = ['menus','menu_items','sidebar_menus'];
        $table = null;
        foreach ($tables as $t) {
            if (Schema::hasTable($t)) { $table = $t; break; }
        }
        if (!$table) { return; }

        $has = fn(string $col) => Schema::hasColumn($table, $col);

        // --- Parent ---
        $parentLabel = 'Jobs Manager';
        DB::table($table)->updateOrInsert(
            ['label' => $parentLabel],
            array_filter([
                'url' => '/dashboard/user/command/jobs',
                'route' => $has('route') ? 'dashboard.user.command.jobs.index' : null,
                'permission' => $has('permission') ? 'jobs.view' : null,
                'icon' => $has('icon') ? 'fa-clipboard-list' : null,
                'parent_id' => $has('parent_id') ? null : null,
                'order' => $has('order') ? 120 : null,
                'created_at' => $has('created_at') ? now() : null,
                'updated_at' => $has('updated_at') ? now() : null,
            ], fn($v) => $v !== null)
        );

        $parentId = DB::table($table)->where('label', $parentLabel)->value('id');

        $children = [
            ['label' => 'Inbox',        'url' => '/dashboard/user/command/jobs',             'route' => 'dashboard.user.command.jobs.index',       'icon' => 'fa-inbox',        'order' => 121],
            ['label' => 'Dispatch',     'url' => '/dashboard/user/command/jobs/dispatch',    'route' => 'dashboard.user.command.jobs.dispatch',    'icon' => 'fa-truck',        'order' => 122],
            ['label' => 'Create Job',   'url' => '/dashboard/user/command/jobs/create',      'route' => 'dashboard.user.command.jobs.create',      'icon' => 'fa-plus',         'order' => 123],
            ['label' => 'Reports',      'url' => '/dashboard/user/command/jobs/reports',     'route' => 'dashboard.user.command.jobs.reports.index','icon' => 'fa-chart-line',   'order' => 124],
            ['label' => 'Settings',     'url' => '/dashboard/user/command/jobs/settings',    'route' => 'dashboard.user.command.jobs.settings',    'icon' => 'fa-gear',         'order' => 125],
        ];

        foreach ($children as $c) {
            DB::table($table)->updateOrInsert(
                ['label' => $c['label']],
                array_filter([
                    'url' => $c['url'],
                    'route' => $has('route') ? $c['route'] : null,
                    'permission' => $has('permission') ? 'jobs.view' : null,
                    'icon' => $has('icon') ? $c['icon'] : null,
                    'parent_id' => $has('parent_id') ? $parentId : null,
                    'order' => $has('order') ? $c['order'] : null,
                    'created_at' => $has('created_at') ? now() : null,
                    'updated_at' => $has('updated_at') ? now() : null,
                ], fn($v) => $v !== null)
            );
        }
    }

    public function down(): void
    {
        $tables = ['menus','menu_items','sidebar_menus'];
        $table = null;
        foreach ($tables as $t) { if (Schema::hasTable($t)) { $table = $t; break; } }
        if (!$table) { return; }
        DB::table($table)->whereIn('label', ['Jobs Manager','Inbox','Dispatch','Create Job','Reports','Settings'])->delete();
    }
};
