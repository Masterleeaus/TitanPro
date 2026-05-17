<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('shifts')) {
            Schema::create('shifts', function (Blueprint $table): void {
                $table->id();
                $table->unsignedInteger('company_id')->nullable()->index();
                $table->string('name')->nullable();
                $table->string('shift_date')->nullable();
                $table->integer('type')->default(1)->comment('1 = recurring, 2 = cyclic, 3 = free');
                $table->unsignedBigInteger('cyclic_duration')->nullable();
                $table->string('start_min_time')->nullable();
                $table->string('start_time')->nullable();
                $table->string('start_max_time')->nullable();
                $table->string('finish_min_time')->nullable();
                $table->string('finish_time')->nullable();
                $table->string('finish_max_time')->nullable();
                $table->unsignedBigInteger('break_time')->nullable();
                $table->unsignedBigInteger('free_work_time')->nullable();
                $table->unsignedBigInteger('free_work_time_range')->nullable();
                $table->string('free_work_time_from')->nullable();
                $table->string('free_work_time_to')->nullable();
                $table->unsignedBigInteger('range')->default(0)->comment('0 = no, 1 = yes');
                $table->string('range_from')->nullable();
                $table->string('range_to')->nullable();
                $table->integer('unhealty_shift')->default(0)->comment('0 = no, 1 = yes');
                $table->string('weekdays')->nullable();
                $table->integer('indefinite')->default(1)->comment('0 = no, 1 = yes');
                $table->string('shift_end_on')->nullable();
                $table->unsignedBigInteger('project_id')->default(0);
                $table->unsignedBigInteger('task_id')->default(0);
                $table->string('tag')->nullable();
                $table->text('note')->nullable();
                $table->integer('publish')->default(1)->comment('0 = no, 1 = yes');
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('assign_shifts')) {
            Schema::create('assign_shifts', function (Blueprint $table): void {
                $table->id();
                $table->unsignedInteger('company_id')->nullable()->index();
                $table->string('department_id')->nullable();
                $table->string('color')->nullable();
                $table->unsignedBigInteger('shift_id')->nullable()->index();
                $table->unsignedBigInteger('employee_id')->nullable()->index();
                $table->integer('extra_hours')->nullable();
                $table->integer('publish')->nullable();
                $table->string('date_added')->nullable();
                $table->string('month_added')->nullable();
                $table->string('year_added')->nullable();
                $table->timestamps();
            });
        }

        Schema::table('assign_shifts', function (Blueprint $table): void {
            if (! Schema::hasColumn('assign_shifts', 'work_order_id')) {
                $table->unsignedBigInteger('work_order_id')->nullable()->index()->after('year_added');
            }
            if (! Schema::hasColumn('assign_shifts', 'appointment_id')) {
                $table->unsignedBigInteger('appointment_id')->nullable()->index()->after('work_order_id');
            }
            if (! Schema::hasColumn('assign_shifts', 'dispatch_status')) {
                $table->string('dispatch_status')->default('scheduled')->after('appointment_id');
            }
            if (! Schema::hasColumn('assign_shifts', 'dispatch_notes')) {
                $table->text('dispatch_notes')->nullable()->after('dispatch_status');
            }
        });
    }

    public function down(): void
    {
        if (Schema::hasTable('assign_shifts')) {
            Schema::table('assign_shifts', function (Blueprint $table): void {
                foreach (['work_order_id', 'appointment_id', 'dispatch_status', 'dispatch_notes'] as $column) {
                    if (Schema::hasColumn('assign_shifts', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }
    }
};
