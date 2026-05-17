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
        Schema::table('tz_portal_operator_bots', function (Blueprint $table) {
            $table->string('footer_link')->nullable()->after('active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tz_portal_operator_bots', function (Blueprint $table) {
            $table->dropColumn('footer_link');
        });
    }
};
