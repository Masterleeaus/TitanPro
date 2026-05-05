<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasTable('projects')) {
            Schema::create('projects', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('code', 50)->unique()->nullable();
                $table->text('description')->nullable();
                $table->string('status')->default('planning');
                $table->string('type')->default('client');
                $table->date('start_date')->nullable();
                $table->date('end_date')->nullable();
                $table->decimal('budget', 15, 2)->nullable();
                $table->string('priority')->default('medium');
                $table->string('color_code', 7)->default('#007bff');
                $table->boolean('is_billable')->default(true);
                $table->decimal('hourly_rate', 8, 2)->nullable();
                $table->unsignedBigInteger('client_id')->nullable();
                $table->unsignedBigInteger('project_manager_id')->nullable();
                $table->unsignedBigInteger('created_by_id')->nullable();
                $table->unsignedBigInteger('updated_by_id')->nullable();
                $table->timestamps();
                $table->softDeletes();
                $table->index(['status', 'type']);
                $table->index(['client_id']);
                $table->index(['project_manager_id']);
                $table->index(['created_by_id']);
            });

            return;
        }

        Schema::table('projects', function (Blueprint $table) {
            if (! Schema::hasColumn('projects', 'code')) {
                $table->string('code', 50)->nullable()->after('name')->index();
            }
            if (! Schema::hasColumn('projects', 'priority')) {
                $table->string('priority')->default('medium')->after('budget');
            }
            if (! Schema::hasColumn('projects', 'color_code')) {
                $table->string('color_code', 7)->default('#007bff')->after('priority');
            }
            if (! Schema::hasColumn('projects', 'is_billable')) {
                $table->boolean('is_billable')->default(true)->after('color_code');
            }
            if (! Schema::hasColumn('projects', 'hourly_rate')) {
                $table->decimal('hourly_rate', 8, 2)->nullable()->after('is_billable');
            }
            if (! Schema::hasColumn('projects', 'client_id')) {
                $table->unsignedBigInteger('client_id')->nullable()->index()->after('hourly_rate');
            }
            if (! Schema::hasColumn('projects', 'project_manager_id')) {
                $table->unsignedBigInteger('project_manager_id')->nullable()->index()->after('client_id');
            }
            if (! Schema::hasColumn('projects', 'created_by_id')) {
                $table->unsignedBigInteger('created_by_id')->nullable()->index()->after('project_manager_id');
            }
            if (! Schema::hasColumn('projects', 'updated_by_id')) {
                $table->unsignedBigInteger('updated_by_id')->nullable()->after('created_by_id');
            }
            if (! Schema::hasColumn('projects', 'deleted_at')) {
                $table->softDeletes();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Keep the legacy projects table intact when it pre-existed. This migration
        // only guarantees compatibility columns for the module.
    }
};
