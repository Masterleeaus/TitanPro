<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public static $prefix = 'tz_portal';

    public function up(): void
    {
        Schema::table(self::$prefix . '_operator_embeddings', function (Blueprint $table) {
            if (! Schema::hasColumn(self::$prefix . '_operator_embeddings', 'type')) {
                $table->string('type')->nullable()->default('text')->after('embedding');
            }
        });
    }

    public function down(): void {}
};
