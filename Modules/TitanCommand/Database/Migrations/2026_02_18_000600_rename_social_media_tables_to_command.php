<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Rebrand: rename legacy social_media_* tables to ext_titan_command_* names.
        // Safe + idempotent: only renames when old table exists and new name does not.

        if (Schema::hasTable('social_media_agents') && !Schema::hasTable('ext_titan_command_agents')) {
            Schema::rename('social_media_agents', 'ext_titan_command_agents');
        }

        if (Schema::hasTable('social_media_agent_posts') && !Schema::hasTable('ext_titan_command_agent_posts')) {
            Schema::rename('social_media_agent_posts', 'ext_titan_command_agent_posts');
        }
    }

    public function down(): void
    {
        // Rollback: rename ext_titan_command_* back to social_media_*
        if (Schema::hasTable('ext_titan_command_agent_posts') && !Schema::hasTable('social_media_agent_posts')) {
            Schema::rename('ext_titan_command_agent_posts', 'social_media_agent_posts');
        }
        if (Schema::hasTable('ext_titan_command_agents') && !Schema::hasTable('social_media_agents')) {
            Schema::rename('ext_titan_command_agents', 'social_media_agents');
        }
    }
};
