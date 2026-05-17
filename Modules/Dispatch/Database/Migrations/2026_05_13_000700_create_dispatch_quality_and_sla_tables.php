<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('dispatch_sla_policies')) {
            Schema::create('dispatch_sla_policies', function (Blueprint $table): void {
                $table->id();
                $table->unsignedInteger('company_id')->nullable()->index();
                $table->string('name');
                $table->string('priority')->default('normal')->index();
                $table->unsignedInteger('response_minutes')->default(60);
                $table->unsignedInteger('completion_minutes')->default(480);
                $table->boolean('active')->default(true)->index();
                $table->json('metadata')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('dispatch_checklists')) {
            Schema::create('dispatch_checklists', function (Blueprint $table): void {
                $table->id();
                $table->unsignedInteger('company_id')->nullable()->index();
                $table->unsignedBigInteger('work_order_id')->index();
                $table->string('name')->default('Site checklist');
                $table->string('status')->default('open')->index();
                $table->unsignedBigInteger('completed_by')->nullable()->index();
                $table->timestamp('completed_at')->nullable();
                $table->json('metadata')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('dispatch_checklist_items')) {
            Schema::create('dispatch_checklist_items', function (Blueprint $table): void {
                $table->id();
                $table->unsignedInteger('company_id')->nullable()->index();
                $table->unsignedBigInteger('checklist_id')->index();
                $table->string('label');
                $table->text('instructions')->nullable();
                $table->boolean('required')->default(true);
                $table->boolean('completed')->default(false)->index();
                $table->unsignedBigInteger('completed_by')->nullable()->index();
                $table->timestamp('completed_at')->nullable();
                $table->unsignedInteger('sort_order')->default(1);
                $table->json('metadata')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('dispatch_exceptions')) {
            Schema::create('dispatch_exceptions', function (Blueprint $table): void {
                $table->id();
                $table->unsignedInteger('company_id')->nullable()->index();
                $table->unsignedBigInteger('work_order_id')->nullable()->index();
                $table->unsignedBigInteger('appointment_id')->nullable()->index();
                $table->unsignedBigInteger('technician_id')->nullable()->index();
                $table->string('type')->index();
                $table->string('severity')->default('medium')->index();
                $table->string('status')->default('open')->index();
                $table->text('message')->nullable();
                $table->unsignedBigInteger('resolved_by')->nullable()->index();
                $table->timestamp('resolved_at')->nullable();
                $table->json('metadata')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('dispatch_exceptions');
        Schema::dropIfExists('dispatch_checklist_items');
        Schema::dropIfExists('dispatch_checklists');
        Schema::dropIfExists('dispatch_sla_policies');
    }
};
