<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('work_orders', function (Blueprint $table) {
            if (! Schema::hasColumn('work_orders', 'budget_amount')) $table->decimal('budget_amount', 12, 2)->default(0)->after('total_estimate');
            if (! Schema::hasColumn('work_orders', 'actual_cost')) $table->decimal('actual_cost', 12, 2)->default(0)->after('budget_amount');
            if (! Schema::hasColumn('work_orders', 'actual_revenue')) $table->decimal('actual_revenue', 12, 2)->default(0)->after('actual_cost');
            if (! Schema::hasColumn('work_orders', 'estimated_hours')) $table->decimal('estimated_hours', 8, 2)->default(0)->after('actual_revenue');
            if (! Schema::hasColumn('work_orders', 'actual_hours')) $table->decimal('actual_hours', 8, 2)->default(0)->after('estimated_hours');
            if (! Schema::hasColumn('work_orders', 'health')) $table->string('health')->default('on_track')->after('actual_hours');
        });
    }

    public function down(): void
    {
        Schema::table('work_orders', function (Blueprint $table) {
            foreach (['health','actual_hours','estimated_hours','actual_revenue','actual_cost','budget_amount'] as $column) {
                if (Schema::hasColumn('work_orders', $column)) $table->dropColumn($column);
            }
        });
    }
};
