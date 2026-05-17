<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Rebrand: remove "legacy tables" table names from DB surface.
        // Safe + idempotent: only renames when old exists and new does not.

        if (Schema::hasTable('ext_titan_command_agents') && !Schema::hasTable('ext_titan_command_agents')) {
            Schema::rename('ext_titan_command_agents', 'ext_titan_command_agents');
        }

        if (Schema::hasTable('ext_titan_command_agent_posts') && !Schema::hasTable('ext_titan_command_agent_posts')) {
            Schema::rename('ext_titan_command_agent_posts', 'ext_titan_command_agent_posts');
        }
    }

    public function down(): void
    {
        // Optional rollback
        if (Schema::hasTable('ext_titan_command_agent_posts') && !Schema::hasTable('ext_titan_command_agent_posts')) {
            Schema::rename('ext_titan_command_agent_posts', 'ext_titan_command_agent_posts');
        }
        if (Schema::hasTable('ext_titan_command_agents') && !Schema::hasTable('ext_titan_command_agents')) {
            Schema::rename('ext_titan_command_agents', 'ext_titan_command_agents');
        }
    }
};
