<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('ext_titan_command_agent_posts')) {
            return;
        }

        Schema::table('ext_titan_command_agent_posts', function (Blueprint $table) {
            if (!Schema::hasColumn('ext_titan_command_agent_posts', 'image_request_id')) {
                $table->string('image_request_id')->nullable()->after('media_urls');
            }

            if (!Schema::hasColumn('ext_titan_command_agent_posts', 'image_status')) {
                $table->string('image_status')->default('none')->after('image_request_id'); // none, pending, completed, failed
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('ext_titan_command_agent_posts')) {
            return;
        }

        Schema::table('ext_titan_command_agent_posts', function (Blueprint $table) {
            if (Schema::hasColumn('ext_titan_command_agent_posts', 'image_status')) {
                $table->dropColumn('image_status');
            }
            if (Schema::hasColumn('ext_titan_command_agent_posts', 'image_request_id')) {
                $table->dropColumn('image_request_id');
            }
        });
    }
};
