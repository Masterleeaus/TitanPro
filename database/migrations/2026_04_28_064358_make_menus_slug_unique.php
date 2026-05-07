<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        $tableName = config('filament-menu-manager.table_prefix', 'fmm_') . 'menus';

        if (! Schema::hasColumn($tableName, 'slug')) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->string('slug')->nullable()->after('name');
            });
        }

        Schema::table($tableName, function (Blueprint $table) {
            $table->unique(['slug']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        $tableName = config('filament-menu-manager.table_prefix', 'fmm_') . 'menus';

        Schema::table($tableName, function (Blueprint $table) {
            $table->dropUnique(['slug']);
        });
    }
};
