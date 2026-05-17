<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('organizations', function (Blueprint $table) {
            if (! Schema::hasColumn('organizations', 'enabled_modules')) {
                $table->json('enabled_modules')->nullable()->after('stripe_customer_id');
            }

            if (! Schema::hasColumn('organizations', 'suspended_at')) {
                $table->timestamp('suspended_at')->nullable()->after('enabled_modules');
            }
        });
    }

    public function down(): void
    {
        Schema::table('organizations', function (Blueprint $table) {
            if (Schema::hasColumn('organizations', 'suspended_at')) {
                $table->dropColumn('suspended_at');
            }

            if (Schema::hasColumn('organizations', 'enabled_modules')) {
                $table->dropColumn('enabled_modules');
            }
        });
    }
};
