<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * @var array<int, string>
     */
    private array $tables = [
        'titan_nexus_leads',
        'ext_contacts',
        'nexus_call_sessions',
        'ext_marketing_conversations',
        'ext_marketing_campaigns',
        'titan_nexus_campaigns',
        'nexus_voice_call_logs',
        'nexus_call_recordings',
        'nexus_call_events',
        'nexus_booking_handoffs',
        'nexus_callback_requests',
        'nexus_contract_documents',
        'titan_automation_runs',
    ];

    public function up(): void
    {
        foreach ($this->tables as $table) {
            if (! Schema::hasTable($table)) {
                continue;
            }

            if (! Schema::hasColumn($table, 'company_id')) {
                Schema::table($table, function (Blueprint $blueprint): void {
                    $blueprint->unsignedBigInteger('company_id')->nullable()->index();
                });
            }

            if ($table === 'titan_nexus_leads' && Schema::hasColumn($table, 'tenant_id')) {
                DB::table($table)
                    ->whereNull('company_id')
                    ->whereNotNull('tenant_id')
                    ->update(['company_id' => DB::raw('tenant_id')]);
            }

            if ($table === 'titan_nexus_campaigns' && Schema::hasColumn($table, 'tenant_id')) {
                DB::table($table)
                    ->whereNull('company_id')
                    ->whereNotNull('tenant_id')
                    ->update(['company_id' => DB::raw('tenant_id')]);
            }

            $indexName = "{$table}_company_id_created_at_index";
            if (! $this->hasIndex($table, $indexName)) {
                Schema::table($table, function (Blueprint $blueprint) use ($indexName): void {
                    $blueprint->index(['company_id', 'created_at'], $indexName);
                });
            }
        }
    }

    public function down(): void
    {
        foreach ($this->tables as $table) {
            if (! Schema::hasTable($table)) {
                continue;
            }

            $indexName = "{$table}_company_id_created_at_index";
            if ($this->hasIndex($table, $indexName)) {
                Schema::table($table, function (Blueprint $blueprint) use ($indexName): void {
                    $blueprint->dropIndex($indexName);
                });
            }
        }
    }

    private function hasIndex(string $table, string $indexName): bool
    {
        if (! Schema::hasTable($table)) {
            return false;
        }

        $schemaBuilder = Schema::getConnection()->getSchemaBuilder();
        if (! method_exists($schemaBuilder, 'getIndexes')) {
            return false;
        }

        /** @var array<int|string, mixed> $indexes */
        $indexes = $schemaBuilder->getIndexes($table);

        return collect($indexes)
            ->map(function (mixed $index, int|string $key): string {
                if (is_array($index) && isset($index['name']) && is_string($index['name'])) {
                    return $index['name'];
                }

                return is_string($key) ? $key : '';
            })
            ->filter()
            ->contains($indexName);
    }
};
