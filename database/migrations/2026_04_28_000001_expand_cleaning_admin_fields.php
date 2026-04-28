<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('field_jobs', function (Blueprint $table) {
            if (! Schema::hasColumn('field_jobs', 'scheduled_end_at')) $table->timestamp('scheduled_end_at')->nullable()->after('scheduled_at');
            if (! Schema::hasColumn('field_jobs', 'access_instructions')) $table->text('access_instructions')->nullable()->after('office_notes');
            if (! Schema::hasColumn('field_jobs', 'rooms_count')) $table->unsignedInteger('rooms_count')->nullable()->after('access_instructions');
            if (! Schema::hasColumn('field_jobs', 'bathrooms_count')) $table->decimal('bathrooms_count', 4, 1)->nullable()->after('rooms_count');
            if (! Schema::hasColumn('field_jobs', 'square_feet')) $table->unsignedInteger('square_feet')->nullable()->after('bathrooms_count');
            if (! Schema::hasColumn('field_jobs', 'recurrence_frequency')) $table->string('recurrence_frequency', 50)->default('none')->after('square_feet');
            if (! Schema::hasColumn('field_jobs', 'recurrence_rule')) $table->string('recurrence_rule')->nullable()->after('recurrence_frequency');
            if (! Schema::hasColumn('field_jobs', 'requires_quality_check')) $table->boolean('requires_quality_check')->default(false)->after('recurrence_rule');
            if (! Schema::hasColumn('field_jobs', 'quality_score')) $table->unsignedTinyInteger('quality_score')->nullable()->after('requires_quality_check');
        });

        Schema::table('job_types', function (Blueprint $table) {
            if (! Schema::hasColumn('job_types', 'service_category')) $table->string('service_category', 80)->nullable()->after('description');
            if (! Schema::hasColumn('job_types', 'default_price')) $table->decimal('default_price', 10, 2)->nullable()->after('service_category');
            if (! Schema::hasColumn('job_types', 'default_duration_minutes')) $table->unsignedInteger('default_duration_minutes')->nullable()->after('default_price');
            if (! Schema::hasColumn('job_types', 'recommended_team_size')) $table->unsignedSmallInteger('recommended_team_size')->default(1)->after('default_duration_minutes');
            if (! Schema::hasColumn('job_types', 'allows_recurring')) $table->boolean('allows_recurring')->default(true)->after('recommended_team_size');
            if (! Schema::hasColumn('job_types', 'requires_quality_check')) $table->boolean('requires_quality_check')->default(false)->after('allows_recurring');
            if (! Schema::hasColumn('job_types', 'required_equipment')) $table->text('required_equipment')->nullable()->after('requires_quality_check');
        });

        Schema::table('items', function (Blueprint $table) {
            if (! Schema::hasColumn('items', 'category')) $table->string('category', 80)->default('cleaning')->after('description');
            if (! Schema::hasColumn('items', 'pricing_type')) $table->string('pricing_type', 50)->default('fixed')->after('category');
            if (! Schema::hasColumn('items', 'estimated_minutes')) $table->unsignedInteger('estimated_minutes')->nullable()->after('unit');
            if (! Schema::hasColumn('items', 'is_upsell')) $table->boolean('is_upsell')->default(true)->after('is_taxable');
            if (! Schema::hasColumn('items', 'injects_checklist_tasks')) $table->boolean('injects_checklist_tasks')->default(false)->after('is_upsell');
        });

        Schema::table('job_checklist_items', function (Blueprint $table) {
            if (! Schema::hasColumn('job_checklist_items', 'organization_id')) $table->foreignId('organization_id')->nullable()->after('id')->constrained()->nullOnDelete();
            if (! Schema::hasColumn('job_checklist_items', 'category')) $table->string('category', 80)->default('general')->after('label');
            if (! Schema::hasColumn('job_checklist_items', 'instructions')) $table->text('instructions')->nullable()->after('category');
            if (! Schema::hasColumn('job_checklist_items', 'estimated_minutes')) $table->unsignedInteger('estimated_minutes')->nullable()->after('instructions');
            if (! Schema::hasColumn('job_checklist_items', 'requires_photo')) $table->boolean('requires_photo')->default(false)->after('is_required');
        });

        // Make job_id nullable so this table can also hold reusable Task Library rows.
        if (Schema::hasColumn('job_checklist_items', 'job_id')) {
            try {
                Schema::table('job_checklist_items', function (Blueprint $table) {
                    $table->foreignId('job_id')->nullable()->change();
                });
            } catch (Throwable $e) {
                // Some MySQL/MariaDB setups cannot change constrained columns without DBAL.
                // Existing job-attached checklists still work; library tasks can be linked after a manual nullable migration if needed.
            }
        }

        Schema::table('job_type_checklist_items', function (Blueprint $table) {
            if (! Schema::hasColumn('job_type_checklist_items', 'task_library_item_id')) $table->foreignId('task_library_item_id')->nullable()->after('job_type_id')->constrained('job_checklist_items')->nullOnDelete();
            if (! Schema::hasColumn('job_type_checklist_items', 'instructions')) $table->text('instructions')->nullable()->after('label');
            if (! Schema::hasColumn('job_type_checklist_items', 'requires_photo')) $table->boolean('requires_photo')->default(false)->after('is_required');
            if (! Schema::hasColumn('job_type_checklist_items', 'condition_type')) $table->string('condition_type', 80)->default('always')->after('requires_photo');
            if (! Schema::hasColumn('job_type_checklist_items', 'condition_value')) $table->string('condition_value')->nullable()->after('condition_type');
        });
    }

    public function down(): void
    {
        Schema::table('field_jobs', function (Blueprint $table) {
            foreach (['scheduled_end_at','access_instructions','rooms_count','bathrooms_count','square_feet','recurrence_frequency','recurrence_rule','requires_quality_check','quality_score'] as $column) {
                if (Schema::hasColumn('field_jobs', $column)) $table->dropColumn($column);
            }
        });
        Schema::table('job_types', function (Blueprint $table) {
            foreach (['service_category','default_price','default_duration_minutes','recommended_team_size','allows_recurring','requires_quality_check','required_equipment'] as $column) {
                if (Schema::hasColumn('job_types', $column)) $table->dropColumn($column);
            }
        });
        Schema::table('items', function (Blueprint $table) {
            foreach (['category','pricing_type','estimated_minutes','is_upsell','injects_checklist_tasks'] as $column) {
                if (Schema::hasColumn('items', $column)) $table->dropColumn($column);
            }
        });
        Schema::table('job_checklist_items', function (Blueprint $table) {
            foreach (['organization_id','category','instructions','estimated_minutes','requires_photo'] as $column) {
                if (Schema::hasColumn('job_checklist_items', $column)) $table->dropColumn($column);
            }
        });
        Schema::table('job_type_checklist_items', function (Blueprint $table) {
            foreach (['task_library_item_id','instructions','requires_photo','condition_type','condition_value'] as $column) {
                if (Schema::hasColumn('job_type_checklist_items', $column)) $table->dropColumn($column);
            }
        });
    }
};
