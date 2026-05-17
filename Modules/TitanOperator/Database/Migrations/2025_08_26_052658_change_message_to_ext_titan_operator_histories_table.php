<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasTable('tz_portal_operator_histories') || ! Schema::hasColumn('tz_portal_operator_histories', 'message')) {
            return;
        }

        if (Schema::getConnection()->getDriverName() === 'sqlite') {
            return;
        }

        Schema::table('tz_portal_operator_histories', function (Blueprint $table) {
            $table->text('message')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('tz_portal_operator_histories') || ! Schema::hasColumn('tz_portal_operator_histories', 'message')) {
            return;
        }

        if (Schema::getConnection()->getDriverName() === 'sqlite') {
            return;
        }

        Schema::table('tz_portal_operator_histories', function (Blueprint $table) {
            $table->text('message')->nullable(false)->change();
        });
    }
};
