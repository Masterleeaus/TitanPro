<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('zeropay_transactions', function (Blueprint $table) {
            if (! Schema::hasColumn('zeropay_transactions', 'type')) {
                $table->string('type', 32)->nullable()->after('session_id');
            }
            if (! Schema::hasColumn('zeropay_transactions', 'payer_name')) {
                $table->string('payer_name')->nullable()->after('status');
            }
            if (! Schema::hasColumn('zeropay_transactions', 'payee_name')) {
                $table->string('payee_name')->nullable()->after('payer_name');
            }
            if (! Schema::hasColumn('zeropay_transactions', 'reference')) {
                $table->string('reference')->nullable()->after('payee_name');
            }
            if (! Schema::hasColumn('zeropay_transactions', 'failure_reason')) {
                $table->text('failure_reason')->nullable()->after('reference');
            }
            if (! Schema::hasColumn('zeropay_transactions', 'gateway_response')) {
                $table->json('gateway_response')->nullable()->after('failure_reason');
            }
        });
    }

    public function down(): void
    {
        Schema::table('zeropay_transactions', function (Blueprint $table) {
            $columns = array_filter([
                'type',
                'payer_name',
                'payee_name',
                'reference',
                'failure_reason',
                'gateway_response',
            ], fn (string $col) => Schema::hasColumn('zeropay_transactions', $col));

            if (! empty($columns)) {
                $table->dropColumn(array_values($columns));
            }
        });
    }
};
