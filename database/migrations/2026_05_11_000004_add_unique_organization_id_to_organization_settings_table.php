<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Deduplicate: for each organization_id keep only the most recent row.
        // SQLite and MySQL both support a DELETE … WHERE id NOT IN (SELECT …) approach,
        // which is portable across the engines used in production (MySQL) and test (SQLite).
        $sub = DB::table('organization_settings')
            ->select(DB::raw('MAX(id) as id'))
            ->groupBy('organization_id');

        DB::table('organization_settings')
            ->whereNotIn('id', $sub->pluck('id'))
            ->delete();

        Schema::table('organization_settings', function (Blueprint $table) {
            $table->unique('organization_id');
        });
    }

    public function down(): void
    {
        Schema::table('organization_settings', function (Blueprint $table) {
            $table->dropUnique(['organization_id']);
        });
    }
};
