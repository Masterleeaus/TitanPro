<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('tasks')) {
            return;
        }

        Schema::table('tasks', function (Blueprint $table) {
            if (! Schema::hasColumn('tasks', 'booking_value')) {
                $table->decimal('booking_value', 12, 2)->nullable()->after('generated_invoice_id');
            }
            if (! Schema::hasColumn('tasks', 'pending_approval_at')) {
                $table->timestamp('pending_approval_at')->nullable()->after('booking_value');
            }
            if (! Schema::hasColumn('tasks', 'approval_due_at')) {
                $table->timestamp('approval_due_at')->nullable()->after('pending_approval_at');
            }
            if (! Schema::hasColumn('tasks', 'approval_decision_at')) {
                $table->timestamp('approval_decision_at')->nullable()->after('approval_due_at');
            }
            if (! Schema::hasColumn('tasks', 'approval_decision_reason')) {
                $table->text('approval_decision_reason')->nullable()->after('approval_decision_at');
            }
            if (! Schema::hasColumn('tasks', 'job_card_completed_at')) {
                $table->timestamp('job_card_completed_at')->nullable()->after('approval_decision_reason');
            }
            if (! Schema::hasColumn('tasks', 'rescheduled_at')) {
                $table->timestamp('rescheduled_at')->nullable()->after('job_card_completed_at');
            }
            if (! Schema::hasColumn('tasks', 'no_show_at')) {
                $table->timestamp('no_show_at')->nullable()->after('rescheduled_at');
            }
            if (! Schema::hasColumn('tasks', 'dispatched_at')) {
                $table->timestamp('dispatched_at')->nullable()->after('no_show_at');
            }
            if (! Schema::hasColumn('tasks', 'paid_at')) {
                $table->timestamp('paid_at')->nullable()->after('dispatched_at');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('tasks')) {
            return;
        }

        Schema::table('tasks', function (Blueprint $table) {
            $columns = [
                'booking_value',
                'pending_approval_at',
                'approval_due_at',
                'approval_decision_at',
                'approval_decision_reason',
                'job_card_completed_at',
                'rescheduled_at',
                'no_show_at',
                'dispatched_at',
                'paid_at',
            ];

            $existing = array_values(array_filter($columns, fn (string $column): bool => Schema::hasColumn('tasks', $column)));
            if ($existing !== []) {
                $table->dropColumn($existing);
            }
        });
    }
};
