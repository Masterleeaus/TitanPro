<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public static $prefix = 'ext';

    /**
     * Run the migrations.
     */
    public function up(): void
    {

        Schema::table(self::$prefix . '_operator_bots', function (Blueprint $table) {
            if (! Schema::hasColumn(self::$prefix . '_operator_bots', 'is_favorite')) {
                $table->boolean('is_favorite')->default(false);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {}
};
