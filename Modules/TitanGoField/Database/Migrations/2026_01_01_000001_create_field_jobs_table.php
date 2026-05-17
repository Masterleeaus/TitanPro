<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('field_jobs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->index();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();

            $table->string('reference')->nullable()->comment('Human-readable WO number');
            $table->unsignedBigInteger('type_id')->nullable();
            $table->unsignedBigInteger('client_id')->nullable();
            $table->unsignedBigInteger('technician_id')->nullable();
            $table->unsignedBigInteger('asset_id')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable();

            $table->string('status', 50)->default('pending');
            $table->string('priority', 50)->default('normal');
            $table->text('description')->nullable();
            $table->text('notes')->nullable();

            $table->timestamp('scheduled_start')->nullable();
            $table->timestamp('scheduled_end')->nullable();
            $table->timestamp('due_at')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();

            $table->string('preferred_date')->nullable();
            $table->string('preferred_time')->nullable();
            $table->text('preferred_note')->nullable();

            // Client portal / signoff
            $table->string('client_portal_token')->nullable()->unique();
            $table->timestamp('client_signed_at')->nullable();
            $table->string('client_signature_path')->nullable();
            $table->string('client_sign_name')->nullable();

            $table->json('meta')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['company_id', 'status']);
            $table->index(['company_id', 'scheduled_start']);
            $table->index(['company_id', 'created_at']);
            $table->index(['company_id', 'technician_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('field_jobs');
    }
};
