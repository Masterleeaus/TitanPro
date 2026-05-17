<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('zeropay_sessions', function (Blueprint $table) {
            $table->timestamp('opened_at')->nullable()->after('expires_at');
            $table->timestamp('completed_at')->nullable()->after('opened_at');
            $table->string('failed_reason')->nullable()->after('completed_at');
        });
    }

    public function down(): void
    {
        Schema::table('zeropay_sessions', function (Blueprint $table) {
            $table->dropColumn(['opened_at', 'completed_at', 'failed_reason']);
        });
    }
};
