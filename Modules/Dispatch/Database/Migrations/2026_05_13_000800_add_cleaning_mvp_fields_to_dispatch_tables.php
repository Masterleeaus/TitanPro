<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('dispatch_work_orders')) {
            Schema::table('dispatch_work_orders', function (Blueprint $table): void {
                if (! Schema::hasColumn('dispatch_work_orders', 'service_type')) {
                    $table->string('service_type')->nullable()->after('priority')->index();
                }
                if (! Schema::hasColumn('dispatch_work_orders', 'arrival_window_start')) {
                    $table->timestamp('arrival_window_start')->nullable()->after('scheduled_for');
                }
                if (! Schema::hasColumn('dispatch_work_orders', 'arrival_window_end')) {
                    $table->timestamp('arrival_window_end')->nullable()->after('arrival_window_start');
                }
            });
        }

        if (Schema::hasTable('dispatch_appointments')) {
            Schema::table('dispatch_appointments', function (Blueprint $table): void {
                if (! Schema::hasColumn('dispatch_appointments', 'checked_in_at')) {
                    $table->timestamp('checked_in_at')->nullable()->after('status');
                }
                if (! Schema::hasColumn('dispatch_appointments', 'checked_out_at')) {
                    $table->timestamp('checked_out_at')->nullable()->after('checked_in_at');
                }
                if (! Schema::hasColumn('dispatch_appointments', 'completion_notes')) {
                    $table->text('completion_notes')->nullable()->after('notes');
                }
                if (! Schema::hasColumn('dispatch_appointments', 'photo_paths')) {
                    $table->json('photo_paths')->nullable()->after('completion_notes');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('dispatch_appointments')) {
            Schema::table('dispatch_appointments', function (Blueprint $table): void {
                foreach (['photo_paths', 'completion_notes', 'checked_out_at', 'checked_in_at'] as $column) {
                    if (Schema::hasColumn('dispatch_appointments', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }

        if (Schema::hasTable('dispatch_work_orders')) {
            Schema::table('dispatch_work_orders', function (Blueprint $table): void {
                foreach (['arrival_window_end', 'arrival_window_start', 'service_type'] as $column) {
                    if (Schema::hasColumn('dispatch_work_orders', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }
    }
};
