<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('ext_marketing_conversations', function (Blueprint $table) {
            if (! Schema::hasColumn('ext_marketing_conversations', 'intent')) {
                $table->string('intent')->nullable()->after('session_id');
            }
            if (! Schema::hasColumn('ext_marketing_conversations', 'intent_confidence')) {
                $table->decimal('intent_confidence', 5, 2)->nullable()->after('intent');
            }
            if (! Schema::hasColumn('ext_marketing_conversations', 'state')) {
                $table->string('state')->default('new')->after('intent_confidence');
            }
            if (! Schema::hasColumn('ext_marketing_conversations', 'goal')) {
                $table->string('goal')->nullable()->after('state');
            }
            if (! Schema::hasColumn('ext_marketing_conversations', 'role_pack')) {
                $table->string('role_pack')->default('titanvox.reception')->after('goal');
            }
            if (! Schema::hasColumn('ext_marketing_conversations', 'handoff_requested_at')) {
                $table->timestamp('handoff_requested_at')->nullable()->after('connect_agent_at');
            }
            if (! Schema::hasColumn('ext_marketing_conversations', 'last_classified_at')) {
                $table->timestamp('last_classified_at')->nullable()->after('handoff_requested_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('ext_marketing_conversations', function (Blueprint $table) {
            foreach (['intent', 'intent_confidence', 'state', 'goal', 'role_pack', 'handoff_requested_at', 'last_classified_at'] as $column) {
                if (Schema::hasColumn('ext_marketing_conversations', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
