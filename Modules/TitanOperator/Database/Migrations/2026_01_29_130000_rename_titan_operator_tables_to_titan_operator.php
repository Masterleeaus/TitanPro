<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

/**
 * Portal table prefix migration.
 * Renames legacy Titan Operator tables into the canonical tz_portal_* namespace.
 * Safe to run on empty installs because every source table is checked first.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();

        $map = [
            'ext_operator_bots' => 'tz_portal_operator_bots',
            'ext_operator_conversations' => 'tz_portal_operator_conversations',
            'ext_operator_messages' => 'tz_portal_operator_messages',
            'ext_operator_histories' => 'tz_portal_operator_histories',
            'ext_operator_embeddings' => 'tz_portal_operator_embeddings',
            'ext_operator_channels' => 'tz_portal_operator_channels',
            'ext_operator_channel_webhooks' => 'tz_portal_operator_channel_webhooks',

            'ext_titan_operator_bots' => 'tz_portal_operator_bots',
            'ext_titan_operator_conversations' => 'tz_portal_operator_conversations',
            'ext_titan_operator_messages' => 'tz_portal_operator_messages',
            'ext_titan_operator_histories' => 'tz_portal_operator_histories',
            'ext_titan_operator_embeddings' => 'tz_portal_operator_embeddings',
            'ext_titan_operator_channels' => 'tz_portal_operator_channels',
            'ext_titan_operator_channel_webhooks' => 'tz_portal_operator_channel_webhooks',
            'ext_titan_operator_customers' => 'tz_portal_operator_customers',
            'ext_titan_operator_knowledge_base_articles' => 'tz_portal_operator_knowledge_base_articles',
            'ext_titan_operator_workflow_runs' => 'tz_portal_operator_workflow_runs',
            'ext_titan_operator_workflow_settings' => 'tz_portal_operator_workflow_settings',
            'ext_titan_operator_tool_settings' => 'tz_portal_operator_tool_settings',
            'ext_titan_operator_avatars' => 'tz_portal_operator_avatars',

            'ext_voice_operator_bots' => 'tz_portal_operator_voice_bots',
            'ext_titan_operator_voice_bots' => 'tz_portal_operator_voice_bots',
            'ext_voiceoperator_histories' => 'tz_portal_operator_voice_histories',
            'ext_titan_operator_voice_histories' => 'tz_portal_operator_voice_histories',
            'ext_voiceoperator_trains' => 'tz_portal_operator_voice_trains',
            'ext_titan_operator_voice_trains' => 'tz_portal_operator_voice_trains',
            'ext_voicechabot_conversations' => 'tz_portal_voice_conversations',
            'ext_voiceoperator_avatars' => 'tz_portal_voiceoperator_avatars',
        ];

        foreach ($map as $from => $to) {
            if (Schema::hasTable($from) && ! Schema::hasTable($to)) {
                Schema::rename($from, $to);
            }
        }

        Schema::enableForeignKeyConstraints();
    }

    public function down(): void
    {
        Schema::disableForeignKeyConstraints();

        $map = [
            'tz_portal_operator_bots' => 'ext_titan_operator_bots',
            'tz_portal_operator_conversations' => 'ext_titan_operator_conversations',
            'tz_portal_operator_messages' => 'ext_titan_operator_messages',
            'tz_portal_operator_histories' => 'ext_titan_operator_histories',
            'tz_portal_operator_embeddings' => 'ext_titan_operator_embeddings',
            'tz_portal_operator_channels' => 'ext_titan_operator_channels',
            'tz_portal_operator_channel_webhooks' => 'ext_titan_operator_channel_webhooks',
            'tz_portal_operator_customers' => 'ext_titan_operator_customers',
            'tz_portal_operator_knowledge_base_articles' => 'ext_titan_operator_knowledge_base_articles',
            'tz_portal_operator_workflow_runs' => 'ext_titan_operator_workflow_runs',
            'tz_portal_operator_workflow_settings' => 'ext_titan_operator_workflow_settings',
            'tz_portal_operator_tool_settings' => 'ext_titan_operator_tool_settings',
            'tz_portal_operator_avatars' => 'ext_titan_operator_avatars',

            'tz_portal_operator_voice_bots' => 'ext_titan_operator_voice_bots',
            'tz_portal_operator_voice_histories' => 'ext_titan_operator_voice_histories',
            'tz_portal_operator_voice_trains' => 'ext_titan_operator_voice_trains',
            'tz_portal_voice_conversations' => 'ext_voicechabot_conversations',
            'tz_portal_voiceoperator_avatars' => 'ext_voiceoperator_avatars',
        ];

        foreach ($map as $from => $to) {
            if (Schema::hasTable($from) && ! Schema::hasTable($to)) {
                Schema::rename($from, $to);
            }
        }

        Schema::enableForeignKeyConstraints();
    }
};
