<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('dispatch_work_orders')) {
            Schema::create('dispatch_work_orders', function (Blueprint $table): void {
                $table->id();
                $table->unsignedInteger('company_id')->nullable()->index();
                $table->unsignedBigInteger('customer_id')->nullable()->index();
                $table->unsignedBigInteger('customer_location_id')->nullable()->index();
                $table->unsignedBigInteger('technician_id')->nullable()->index();
                $table->string('title')->nullable();
                $table->string('reference')->nullable()->index();
                $table->string('status')->default('draft')->index();
                $table->string('priority')->default('normal')->index();
                $table->text('description')->nullable();
                $table->text('notes')->nullable();
                $table->string('location')->nullable();
                $table->decimal('estimated_hours', 8, 2)->nullable();
                $table->timestamp('scheduled_for')->nullable()->index();
                $table->timestamp('started_at')->nullable();
                $table->timestamp('completed_at')->nullable();
                $table->json('metadata')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('dispatch_appointments')) {
            Schema::create('dispatch_appointments', function (Blueprint $table): void {
                $table->id();
                $table->unsignedInteger('company_id')->nullable()->index();
                $table->unsignedBigInteger('work_order_id')->nullable()->index();
                $table->unsignedBigInteger('technician_id')->nullable()->index();
                $table->unsignedBigInteger('shift_id')->nullable()->index();
                $table->unsignedBigInteger('customer_location_id')->nullable()->index();
                $table->timestamp('starts_at')->nullable()->index();
                $table->timestamp('ends_at')->nullable();
                $table->date('start_date')->nullable()->index();
                $table->time('start_time')->nullable();
                $table->date('end_date')->nullable();
                $table->time('end_time')->nullable();
                $table->string('location')->nullable();
                $table->string('status')->default('scheduled')->index();
                $table->text('notes')->nullable();
                $table->json('metadata')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('dispatch_appointments');
        Schema::dropIfExists('dispatch_work_orders');
    }
};
