<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('job_line_items', function (Blueprint $table) {
            $table->unique(['job_id', 'sort_order'], 'job_line_items_job_id_sort_order_unique');
        });
    }

    public function down(): void
    {
        Schema::table('job_line_items', function (Blueprint $table) {
            $table->dropUnique('job_line_items_job_id_sort_order_unique');
        });
    }
};
