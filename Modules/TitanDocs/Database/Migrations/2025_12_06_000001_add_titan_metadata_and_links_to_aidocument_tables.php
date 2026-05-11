<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Extra metadata for histories
        Schema::table('ai_prompt_histories', function (Blueprint $table) {
            if (!Schema::hasColumn('ai_prompt_histories', 'titan_profile')) {
                $table->string('titan_profile')->nullable()->after('language');
            }
            if (!Schema::hasColumn('ai_prompt_histories', 'titan_model')) {
                $table->string('titan_model')->nullable()->after('titan_profile');
            }
            if (!Schema::hasColumn('ai_prompt_histories', 'titan_meta')) {
                $table->json('titan_meta')->nullable()->after('titan_model');
            }
            if (!Schema::hasColumn('ai_prompt_histories', 'project_id')) {
                $table->unsignedBigInteger('project_id')->nullable()->after('workspace');
            }
            if (!Schema::hasColumn('ai_prompt_histories', 'client_id')) {
                $table->unsignedBigInteger('client_id')->nullable()->after('project_id');
            }
            if (!Schema::hasColumn('ai_prompt_histories', 'template_version')) {
                $table->string('template_version', 50)->nullable()->after('template_id');
            }
        });

        // Extra metadata for responses
        Schema::table('ai_prompt_responses', function (Blueprint $table) {
            if (!Schema::hasColumn('ai_prompt_responses', 'titan_profile')) {
                $table->string('titan_profile')->nullable()->after('used_words');
            }
            if (!Schema::hasColumn('ai_prompt_responses', 'titan_model')) {
                $table->string('titan_model')->nullable()->after('titan_profile');
            }
            if (!Schema::hasColumn('ai_prompt_responses', 'titan_meta')) {
                $table->json('titan_meta')->nullable()->after('titan_model');
            }
            if (!Schema::hasColumn('ai_prompt_responses', 'project_id')) {
                $table->unsignedBigInteger('project_id')->nullable()->after('history_prompt_id');
            }
            if (!Schema::hasColumn('ai_prompt_responses', 'client_id')) {
                $table->unsignedBigInteger('client_id')->nullable()->after('project_id');
            }
            if (!Schema::hasColumn('ai_prompt_responses', 'template_version')) {
                $table->string('template_version', 50)->nullable()->after('template_id');
            }
        });

        // Template-level system prompt + version
        Schema::table('ai_templates', function (Blueprint $table) {
            if (!Schema::hasColumn('ai_templates', 'system_prompt')) {
                $table->text('system_prompt')->nullable()->after('description');
            }
            if (!Schema::hasColumn('ai_templates', 'version')) {
                $table->string('version', 50)->nullable()->after('system_prompt');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ai_prompt_histories', function (Blueprint $table) {
            if (Schema::hasColumn('ai_prompt_histories', 'titan_profile')) {
                $table->dropColumn('titan_profile');
            }
            if (Schema::hasColumn('ai_prompt_histories', 'titan_model')) {
                $table->dropColumn('titan_model');
            }
            if (Schema::hasColumn('ai_prompt_histories', 'titan_meta')) {
                $table->dropColumn('titan_meta');
            }
            if (Schema::hasColumn('ai_prompt_histories', 'project_id')) {
                $table->dropColumn('project_id');
            }
            if (Schema::hasColumn('ai_prompt_histories', 'client_id')) {
                $table->dropColumn('client_id');
            }
            if (Schema::hasColumn('ai_prompt_histories', 'template_version')) {
                $table->dropColumn('template_version');
            }
        });

        Schema::table('ai_prompt_responses', function (Blueprint $table) {
            if (Schema::hasColumn('ai_prompt_responses', 'titan_profile')) {
                $table->dropColumn('titan_profile');
            }
            if (Schema::hasColumn('ai_prompt_responses', 'titan_model')) {
                $table->dropColumn('titan_model');
            }
            if (Schema::hasColumn('ai_prompt_responses', 'titan_meta')) {
                $table->dropColumn('titan_meta');
            }
            if (Schema::hasColumn('ai_prompt_responses', 'project_id')) {
                $table->dropColumn('project_id');
            }
            if (Schema::hasColumn('ai_prompt_responses', 'client_id')) {
                $table->dropColumn('client_id');
            }
            if (Schema::hasColumn('ai_prompt_responses', 'template_version')) {
                $table->dropColumn('template_version');
            }
        });

        Schema::table('ai_templates', function (Blueprint $table) {
            if (Schema::hasColumn('ai_templates', 'system_prompt')) {
                $table->dropColumn('system_prompt');
            }
            if (Schema::hasColumn('ai_templates', 'version')) {
                $table->dropColumn('version');
            }
        });
    }
};
