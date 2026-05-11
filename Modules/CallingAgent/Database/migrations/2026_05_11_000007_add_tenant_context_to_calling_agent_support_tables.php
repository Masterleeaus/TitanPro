<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        foreach ($this->tenantTables() as $table) {
            if (! Schema::hasTable($table)) {
                continue;
            }

            Schema::table($table, function (Blueprint $blueprint) use ($table): void {
                if (! Schema::hasColumn($table, 'tenant_id')) {
                    $blueprint->unsignedBigInteger('tenant_id')->nullable()->index()->after('id');
                }
            });
        }

        if (Schema::hasTable('calling_agent_caller_profiles')) {
            Schema::table('calling_agent_caller_profiles', function (Blueprint $blueprint): void {
                if (! Schema::hasColumn('calling_agent_caller_profiles', 'last_call_at')) {
                    $blueprint->timestamp('last_call_at')->nullable()->after('last_seen_at');
                }

                if (! Schema::hasColumn('calling_agent_caller_profiles', 'call_count')) {
                    $blueprint->unsignedInteger('call_count')->default(0)->after('last_call_at');
                }
            });
        }

        if (Schema::hasTable('calling_agent_usage_records')) {
            Schema::table('calling_agent_usage_records', function (Blueprint $blueprint): void {
                if (! Schema::hasColumn('calling_agent_usage_records', 'idempotency_key')) {
                    $blueprint->string('idempotency_key')->nullable()->unique()->after('tenant_id');
                }
            });
        }
    }

    public function down(): void
    {
        foreach ($this->tenantTables() as $table) {
            if (! Schema::hasTable($table) || ! Schema::hasColumn($table, 'tenant_id')) {
                continue;
            }

            Schema::table($table, function (Blueprint $blueprint) use ($table): void {
                $blueprint->dropColumn('tenant_id');
            });
        }

        if (Schema::hasTable('calling_agent_caller_profiles')) {
            Schema::table('calling_agent_caller_profiles', function (Blueprint $blueprint): void {
                $columns = array_filter(
                    ['last_call_at', 'call_count'],
                    fn (string $column): bool => Schema::hasColumn('calling_agent_caller_profiles', $column),
                );

                if ($columns !== []) {
                    $blueprint->dropColumn($columns);
                }
            });
        }

        if (Schema::hasTable('calling_agent_usage_records')
            && Schema::hasColumn('calling_agent_usage_records', 'idempotency_key')) {
            Schema::table('calling_agent_usage_records', function (Blueprint $blueprint): void {
                $blueprint->dropUnique(['idempotency_key']);
                $blueprint->dropColumn('idempotency_key');
            });
        }
    }

    /**
     * @return list<string>
     */
    private function tenantTables(): array
    {
        return [
            'calling_agent_caller_profiles',
            'calling_agent_call_outcomes',
            'calling_agent_booking_requests',
            'calling_agent_realtime_sessions',
            'calling_agent_recordings',
            'calling_agent_webhook_idempotency',
            'calling_agent_transfer_attempts',
            'calling_agent_missed_call_recovery_tasks',
        ];
    }
};
