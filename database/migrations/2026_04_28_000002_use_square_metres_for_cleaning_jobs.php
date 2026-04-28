<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('field_jobs', function (Blueprint $table) {
            if (! Schema::hasColumn('field_jobs', 'square_metres')) {
                $after = Schema::hasColumn('field_jobs', 'bathrooms_count') ? 'bathrooms_count' : null;
                $column = $table->decimal('square_metres', 8, 1)->nullable();
                if ($after) {
                    $column->after($after);
                }
            }
        });

        if (Schema::hasColumn('field_jobs', 'square_feet') && Schema::hasColumn('field_jobs', 'square_metres')) {
            try {
                DB::statement('UPDATE field_jobs SET square_metres = ROUND(square_feet * 0.092903, 1) WHERE square_metres IS NULL AND square_feet IS NOT NULL');
            } catch (Throwable $e) {
                // Safe no-op for drivers that do not support this statement during deploy.
            }
        }
    }

    public function down(): void
    {
        Schema::table('field_jobs', function (Blueprint $table) {
            if (Schema::hasColumn('field_jobs', 'square_metres')) {
                $table->dropColumn('square_metres');
            }
        });
    }
};
