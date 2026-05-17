<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('complaint_replies') && ! Schema::hasColumn('complaint_replies', 'company_id')) {
            Schema::table('complaint_replies', function (Blueprint $table): void {
                $table->unsignedInteger('company_id')->nullable()->after('complaint_id');
                $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade')->onUpdate('cascade');
            });
        }

        if (Schema::hasTable('complaint_files') && ! Schema::hasColumn('complaint_files', 'company_id')) {
            Schema::table('complaint_files', function (Blueprint $table): void {
                $table->unsignedInteger('company_id')->nullable()->after('id');
                $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade')->onUpdate('cascade');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('complaint_replies') && Schema::hasColumn('complaint_replies', 'company_id')) {
            Schema::table('complaint_replies', function (Blueprint $table): void {
                $table->dropForeign(['company_id']);
                $table->dropColumn('company_id');
            });
        }

        if (Schema::hasTable('complaint_files') && Schema::hasColumn('complaint_files', 'company_id')) {
            Schema::table('complaint_files', function (Blueprint $table): void {
                $table->dropForeign(['company_id']);
                $table->dropColumn('company_id');
            });
        }
    }
};
