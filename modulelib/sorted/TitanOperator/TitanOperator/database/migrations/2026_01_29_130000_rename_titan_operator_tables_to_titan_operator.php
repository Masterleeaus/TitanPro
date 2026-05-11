<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Titan Operator hard rename: rename legacy TitanOperator tables to Titan Operator tables.
 * Safe to run on empty installs (checks table existence).
 */
return new class extends Migration
{
    public function up(): void
    {
        // If there is no live data, we can rename tables directly.
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        $map = [
            'ext_operator_bots' => 'ext_titan_operator_bots',
            'ext_titan_operator_conversations' => 'ext_titan_operator_conversations',
            'ext_titan_operator_histories' => 'ext_titan_operator_histories',
            'ext_titan_operator_customers' => 'ext_titan_operator_customers',
            'ext_titan_operator_knowledge_base_articles' => 'ext_titan_operator_knowledge_base_articles',
            'ext_titan_operator_workflow_runs' => 'ext_titan_operator_workflow_runs',
            'ext_titan_operator_workflow_settings' => 'ext_titan_operator_workflow_settings',
            'ext_titan_operator_tool_settings' => 'ext_titan_operator_tool_settings',

            // Voice tables (if present)
            'ext_voice_operator_bots' => 'ext_titan_operator_voice_bots',
            'ext_voiceoperator_histories' => 'ext_titan_operator_voice_histories',
            'ext_voiceoperator_trains' => 'ext_titan_operator_voice_trains',
        ];

        foreach ($map as $from => $to) {
            if (Schema::hasTable($from) && !Schema::hasTable($to)) {
                Schema::rename($from, $to);
            }
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        $map = [
            'ext_titan_operator_bots' => 'ext_operator_bots',
            'ext_titan_operator_conversations' => 'ext_titan_operator_conversations',
            'ext_titan_operator_histories' => 'ext_titan_operator_histories',
            'ext_titan_operator_customers' => 'ext_titan_operator_customers',
            'ext_titan_operator_knowledge_base_articles' => 'ext_titan_operator_knowledge_base_articles',
            'ext_titan_operator_workflow_runs' => 'ext_titan_operator_workflow_runs',
            'ext_titan_operator_workflow_settings' => 'ext_titan_operator_workflow_settings',
            'ext_titan_operator_tool_settings' => 'ext_titan_operator_tool_settings',

            'ext_titan_operator_voice_bots' => 'ext_voice_operator_bots',
            'ext_titan_operator_voice_histories' => 'ext_voiceoperator_histories',
            'ext_titan_operator_voice_trains' => 'ext_voiceoperator_trains',
        ];

        foreach ($map as $from => $to) {
            if (Schema::hasTable($from) && !Schema::hasTable($to)) {
                Schema::rename($from, $to);
            }
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
