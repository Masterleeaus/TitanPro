<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tz_portal_operator_customers', function (Blueprint $table) {
            $table->bigInteger('user_id')->after('id')->nullable();
        });
    }

    public function down(): void
    {
        if (Schema::hasTable('tz_portal_operator_customers') && Schema::hasColumn('tz_portal_operator_customers', 'user_id')) {
            Schema::table('tz_portal_operator_customers', function (Blueprint $table): void {
                $table->dropColumn('user_id');
            });
        }
    }
};
