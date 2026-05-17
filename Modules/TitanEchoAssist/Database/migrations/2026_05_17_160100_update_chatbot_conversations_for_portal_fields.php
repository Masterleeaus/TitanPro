<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('ext_chatbot_conversations')) {
            return;
        }

        Schema::table('ext_chatbot_conversations', function (Blueprint $table) {
            if (! Schema::hasColumn('ext_chatbot_conversations', 'email')) {
                $table->string('email')->nullable()->after('conversation_name');
            }

            if (! Schema::hasColumn('ext_chatbot_conversations', 'rating')) {
                $table->unsignedTinyInteger('rating')->nullable()->after('email');
            }

            if (! Schema::hasColumn('ext_chatbot_conversations', 'reviewed_at')) {
                $table->dateTime('reviewed_at')->nullable()->after('rating');
            }

            if (! Schema::hasColumn('ext_chatbot_conversations', 'internal_notes')) {
                $table->text('internal_notes')->nullable()->after('last_activity_at');
            }

            if (! Schema::hasColumn('ext_chatbot_conversations', 'is_pinned')) {
                $table->boolean('is_pinned')->default(false)->after('pinned');
            }

            if (! Schema::hasColumn('ext_chatbot_conversations', 'source_channel')) {
                $table->string('source_channel')->nullable()->after('chatbot_channel');
            }

            if (! Schema::hasColumn('ext_chatbot_conversations', 'voice_call_duration')) {
                $table->unsignedInteger('voice_call_duration')->nullable()->after('source_channel');
            }

            if (! Schema::hasColumn('ext_chatbot_conversations', 'voice_recording_url')) {
                $table->string('voice_recording_url')->nullable()->after('voice_call_duration');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('ext_chatbot_conversations')) {
            return;
        }

        Schema::table('ext_chatbot_conversations', function (Blueprint $table) {
            $columns = [
                'email',
                'rating',
                'reviewed_at',
                'internal_notes',
                'is_pinned',
                'source_channel',
                'voice_call_duration',
                'voice_recording_url',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('ext_chatbot_conversations', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
