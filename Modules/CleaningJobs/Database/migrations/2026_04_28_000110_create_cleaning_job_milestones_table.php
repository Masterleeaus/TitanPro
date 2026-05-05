<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('cleaning_job_milestones')) {
            Schema::create('cleaning_job_milestones', function (Blueprint $table) {
                $table->id();
                $table->foreignId('work_order_id')->constrained('work_orders')->cascadeOnDelete();
                $table->string('title');
                $table->text('summary')->nullable();
                $table->string('status')->default('pending');
                $table->decimal('budget_amount', 12, 2)->default(0);
                $table->unsignedTinyInteger('progress')->default(0);
                $table->date('start_date')->nullable();
                $table->date('end_date')->nullable();
                $table->unsignedInteger('order')->default(0);
                $table->unsignedBigInteger('company_id')->nullable()->index();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('cleaning_job_milestones');
    }
};
