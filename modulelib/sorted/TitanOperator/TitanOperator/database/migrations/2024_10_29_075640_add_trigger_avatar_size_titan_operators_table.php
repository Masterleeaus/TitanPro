<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public static $prefix = 'ext';

    public function up(): void
    {
        Schema::table(self::$prefix . '_operator_bots', function (Blueprint $table) {
            if (! Schema::hasColumn(self::$prefix . '_operator_bots', 'trigger_avatar_size')) {
                $table->string('trigger_avatar_size')->nullable()->default(60);
            }
            if (! Schema::hasColumn(self::$prefix . '_operator_bots', 'trigger_background')) {
                $table->string('trigger_background')->nullable();
            }
            if (! Schema::hasColumn(self::$prefix . '_operator_bots', 'trigger_foreground')) {
                $table->string('trigger_foreground')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table(self::$prefix . '_operator_bots', function (Blueprint $table) {
            $table->dropColumn('trigger_avatar_size');
            $table->dropColumn('trigger_background');
            $table->dropColumn('trigger_foreground');
        });
    }
};
