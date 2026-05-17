<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tz_portal_operator_conversations', function (Blueprint $table) {
            $table->string('ticket_status')->default('new')->after('is_showed_on_history');
        });
    }

    public function down(): void
    {
        Schema::table('tz_portal_operator_conversations', function (Blueprint $table) {
            $table->dropColumn('ticket_status');
        });
    }
};
