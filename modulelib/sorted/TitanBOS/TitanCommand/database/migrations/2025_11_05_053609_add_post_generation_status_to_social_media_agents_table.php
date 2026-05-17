<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('ext_titan_command_agents')) {
            return;
        }

        Schema::table('ext_titan_command_agents', function (Blueprint $table) {
            if (!Schema::hasColumn('ext_titan_command_agents', 'post_generation_status')) {
                $table->json('post_generation_status')->nullable()->after('start_train_post_count');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('ext_titan_command_agents')) {
            return;
        }

        Schema::table('ext_titan_command_agents', function (Blueprint $table) {
            if (Schema::hasColumn('ext_titan_command_agents', 'post_generation_status')) {
                $table->dropColumn('post_generation_status');
            }
        });
    }
};
