<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Catalog of reusable service parts
        Schema::create('fsm_service_parts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->index();
            $table->string('title');
            $table->string('sku')->nullable();
            $table->string('unit', 30)->nullable();
            $table->decimal('price', 12, 2)->default(0);
            $table->text('description')->nullable();
            $table->timestamps();

            $table->index(['company_id', 'sku']);
        });

        // Parts used on a specific field job
        Schema::create('field_job_service_parts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->index();
            $table->unsignedBigInteger('field_job_id');
            $table->unsignedBigInteger('service_part_id')->nullable();
            $table->string('type', 30)->default('part')->comment('part or service');
            $table->string('item_name')->nullable();
            $table->decimal('qty', 12, 4)->default(1);
            $table->decimal('unit_price', 12, 2)->default(0);
            $table->decimal('amount', 12, 2)->default(0);
            $table->timestamps();

            $table->index(['company_id', 'field_job_id']);
            $table->foreign('field_job_id')->references('id')->on('field_jobs')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('field_job_service_parts');
        Schema::dropIfExists('fsm_service_parts');
    }
};
