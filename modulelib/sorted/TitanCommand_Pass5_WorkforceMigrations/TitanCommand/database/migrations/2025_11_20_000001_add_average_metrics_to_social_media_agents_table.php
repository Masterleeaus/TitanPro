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
            if (!Schema::hasColumn('ext_titan_command_agents', 'average_impressions')) {
                $table->unsignedBigInteger('average_impressions')->nullable()->after('post_generation_status');
            }

            if (!Schema::hasColumn('ext_titan_command_agents', 'average_engagement')) {
                $table->decimal('average_engagement', 10, 2)->nullable()->after('average_impressions');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('ext_titan_command_agents')) {
            return;
        }

        Schema::table('ext_titan_command_agents', function (Blueprint $table) {
            if (Schema::hasColumn('ext_titan_command_agents', 'average_engagement')) {
                $table->dropColumn('average_engagement');
            }

            if (Schema::hasColumn('ext_titan_command_agents', 'average_impressions')) {
                $table->dropColumn('average_impressions');
            }
        });
    }
};
