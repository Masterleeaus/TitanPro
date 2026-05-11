<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public static $prefix = 'ext';

    public function up(): void
    {
        Schema::table(self::$prefix . '_operator_messages', function (Blueprint $table) {
            if (! Schema::hasColumn(self::$prefix . '_operator_messages', 'model')) {
                $table->string('model')->nullable();
            }

            if (! Schema::hasColumn(self::$prefix . '_operator_messages', 'role')) {
                $table->string('role')->nullable();
            }

            if (Schema::hasColumn(self::$prefix . '_operator_messages', 'is_visitor')) {
                $table->dropColumn('is_visitor');
            }
        });
    }

    public function down(): void
    {
        Schema::table(self::$prefix . '_operator_messages', function (Blueprint $table) {
            if (Schema::hasColumn(self::$prefix . '_operator_messages', 'model')) {
                $table->dropColumn('model');
            }

            if (Schema::hasColumn(self::$prefix . '_operator_messages', 'role')) {
                $table->dropColumn('role');
            }

            if (! Schema::hasColumn(self::$prefix . '_operator_messages', 'is_visitor')) {
                $table->boolean('is_visitor')->default(false);
            }
        });
    }
};
