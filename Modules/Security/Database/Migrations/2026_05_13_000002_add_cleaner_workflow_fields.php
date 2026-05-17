<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('security_cleaners')) {
            Schema::table('security_cleaners', function (Blueprint $table) {
                if (! Schema::hasColumn('security_cleaners', 'rejected_by')) {
                    $table->unsignedBigInteger('rejected_by')->nullable()->index()->after('approved_at');
                }
                if (! Schema::hasColumn('security_cleaners', 'rejected_at')) {
                    $table->timestamp('rejected_at')->nullable()->after('rejected_by');
                }
                if (! Schema::hasColumn('security_cleaners', 'suspended_by')) {
                    $table->unsignedBigInteger('suspended_by')->nullable()->index()->after('rejected_at');
                }
                if (! Schema::hasColumn('security_cleaners', 'suspended_at')) {
                    $table->timestamp('suspended_at')->nullable()->after('suspended_by');
                }
                if (! Schema::hasColumn('security_cleaners', 'decision_reason')) {
                    $table->text('decision_reason')->nullable()->after('suspended_at');
                }
                if (! Schema::hasColumn('security_cleaners', 'access_expires_at')) {
                    $table->timestamp('access_expires_at')->nullable()->index()->after('decision_reason');
                }
            });
        }

        if (Schema::hasTable('security_cleaner_site_logs')) {
            Schema::table('security_cleaner_site_logs', function (Blueprint $table) {
                if (! Schema::hasColumn('security_cleaner_site_logs', 'forced_checkout')) {
                    $table->boolean('forced_checkout')->default(false)->index()->after('status');
                }
                if (! Schema::hasColumn('security_cleaner_site_logs', 'duration_minutes')) {
                    $table->unsignedInteger('duration_minutes')->nullable()->after('forced_checkout');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('security_cleaner_site_logs')) {
            Schema::table('security_cleaner_site_logs', function (Blueprint $table) {
                foreach (['forced_checkout', 'duration_minutes'] as $column) {
                    if (Schema::hasColumn('security_cleaner_site_logs', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }

        if (Schema::hasTable('security_cleaners')) {
            Schema::table('security_cleaners', function (Blueprint $table) {
                foreach (['rejected_by', 'rejected_at', 'suspended_by', 'suspended_at', 'decision_reason', 'access_expires_at'] as $column) {
                    if (Schema::hasColumn('security_cleaners', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }
    }
};
