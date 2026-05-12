<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('ext_marketing_conversations', function (Blueprint $table) {
            if (!Schema::hasColumn('ext_marketing_conversations', 'support_status')) {
                $table->string('support_status')->nullable()->index(); // open|waiting_customer|waiting_us|closed
            }
            if (!Schema::hasColumn('ext_marketing_conversations', 'support_sla_due_at')) {
                $table->timestamp('support_sla_due_at')->nullable();
            }
            if (!Schema::hasColumn('ext_marketing_conversations', 'support_owner_id')) {
                $table->unsignedBigInteger('support_owner_id')->nullable()->index();
            }
            if (!Schema::hasColumn('ext_marketing_conversations', 'support_tags')) {
                $table->json('support_tags')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('ext_marketing_conversations', function (Blueprint $table) {
            if (Schema::hasColumn('ext_marketing_conversations', 'support_status')) {
                $table->dropColumn('support_status');
            }
            if (Schema::hasColumn('ext_marketing_conversations', 'support_sla_due_at')) {
                $table->dropColumn('support_sla_due_at');
            }
            if (Schema::hasColumn('ext_marketing_conversations', 'support_owner_id')) {
                $table->dropColumn('support_owner_id');
            }
            if (Schema::hasColumn('ext_marketing_conversations', 'support_tags')) {
                $table->dropColumn('support_tags');
            }
        });
    }
};
