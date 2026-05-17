<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public static $prefix = 'ext';

    public function up(): void
    {
        if (Schema::hasColumn(self::$prefix . '_operator_conversations', 'operator_channel')) {
            return;
        }

        Schema::table(self::$prefix . '_operator_conversations', function (Blueprint $table) {
            $table->string('operator_channel')->nullable()->after('id')->default('frame');
        });
    }

    public function down(): void
    {
        Schema::table(self::$prefix . '_operator_conversations', function (Blueprint $table) {
            $table->dropColumn('operator_channel');
        });
    }
};
