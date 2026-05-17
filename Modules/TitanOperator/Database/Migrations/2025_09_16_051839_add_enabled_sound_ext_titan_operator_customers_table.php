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
        Schema::table('tz_portal_operator_customers', function (Blueprint $table) {
            $table->boolean('enabled_sound')->default(true)->after('operator_channel');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('tz_portal_operator_customers') || ! Schema::hasColumn('tz_portal_operator_customers', 'enabled_sound')) {
            return;
        }

        Schema::table('tz_portal_operator_customers', function (Blueprint $table) {
            $table->dropColumn('enabled_sound');
        });
    }
};
