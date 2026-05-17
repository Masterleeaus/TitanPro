<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('work_orders', function (Blueprint $table): void {
            if (! Schema::hasColumn('work_orders', 'company_id')) {
                $table->unsignedBigInteger('company_id')->nullable()->after('parent_id')->index();
            }
        });
    }

    public function down(): void
    {
        Schema::table('work_orders', function (Blueprint $table): void {
            if (Schema::hasColumn('work_orders', 'company_id')) {
                $table->dropColumn('company_id');
            }
        });
    }
};
