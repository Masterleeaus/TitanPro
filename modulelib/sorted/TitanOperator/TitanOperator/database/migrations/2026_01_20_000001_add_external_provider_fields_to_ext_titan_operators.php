<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('ext_titan_operator_bots', function (Blueprint $table) {
            // External provider mode (optional)
            if (!Schema::hasColumn('ext_titan_operator_bots', 'provider_type')) {
                $table->string('provider_type', 32)->nullable()->after('human_agent_conditions');
            }
            if (!Schema::hasColumn('ext_titan_operator_bots', 'external_endpoint_url')) {
                $table->text('external_endpoint_url')->nullable()->after('provider_type');
            }
            if (!Schema::hasColumn('ext_titan_operator_bots', 'external_auth_type')) {
                $table->string('external_auth_type', 32)->nullable()->after('external_endpoint_url');
            }
            if (!Schema::hasColumn('ext_titan_operator_bots', 'external_auth_token')) {
                $table->text('external_auth_token')->nullable()->after('external_auth_type');
            }
            if (!Schema::hasColumn('ext_titan_operator_bots', 'external_signing_secret')) {
                $table->text('external_signing_secret')->nullable()->after('external_auth_token');
            }
            if (!Schema::hasColumn('ext_titan_operator_bots', 'external_timeout_ms')) {
                $table->integer('external_timeout_ms')->nullable()->after('external_signing_secret');
            }
        });
    }

    public function down(): void
    {
        Schema::table('ext_titan_operator_bots', function (Blueprint $table) {
            foreach (['provider_type','external_endpoint_url','external_auth_type','external_auth_token','external_signing_secret','external_timeout_ms'] as $col) {
                if (Schema::hasColumn('ext_titan_operator_bots', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
