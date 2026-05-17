<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('ext_titan_command_agents')) {
            Schema::table('ext_titan_command_agents', function (Blueprint $table) {
                if (!Schema::hasColumn('ext_titan_command_agents', 'company_id')) {
                    $table->bigInteger('company_id')->nullable()->after('id');
                }
                if (!Schema::hasColumn('ext_titan_command_agents', 'team_id')) {
                    $table->bigInteger('team_id')->nullable()->after('user_id');
                }
                if (!Schema::hasColumn('ext_titan_command_agents', 'created_by_team_id')) {
                    $table->bigInteger('created_by_team_id')->nullable()->after('team_id');
                }
            });

            // Backfill company_id to match user_id where missing (MVP scoping rule)
            DB::table('ext_titan_command_agents')
                ->whereNull('company_id')
                ->whereNotNull('user_id')
                ->update(['company_id' => DB::raw('user_id')]);
        }

        if (Schema::hasTable('ext_titan_command_agent_posts')) {
            Schema::table('ext_titan_command_agent_posts', function (Blueprint $table) {
                if (!Schema::hasColumn('ext_titan_command_agent_posts', 'company_id')) {
                    $table->bigInteger('company_id')->nullable()->after('id');
                }
                if (!Schema::hasColumn('ext_titan_command_agent_posts', 'user_id')) {
                    $table->bigInteger('user_id')->nullable()->after('company_id');
                }
            });

            DB::table('ext_titan_command_agent_posts')
                ->whereNull('company_id')
                ->whereNotNull('user_id')
                ->update(['company_id' => DB::raw('user_id')]);
        }
    }

    public function down(): void
    {
        // Non-destructive: do not drop columns automatically.
    }
};
