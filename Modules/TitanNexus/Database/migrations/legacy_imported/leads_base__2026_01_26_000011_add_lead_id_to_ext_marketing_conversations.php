<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('ext_marketing_conversations') && !Schema::hasColumn('ext_marketing_conversations', 'lead_id')) {
            Schema::table('ext_marketing_conversations', function (Blueprint $table) {
                $table->unsignedBigInteger('lead_id')->nullable()->after('contact_id')->index();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('ext_marketing_conversations') && Schema::hasColumn('ext_marketing_conversations', 'lead_id')) {
            Schema::table('ext_marketing_conversations', function (Blueprint $table) {
                $table->dropColumn('lead_id');
            });
        }
    }
};
