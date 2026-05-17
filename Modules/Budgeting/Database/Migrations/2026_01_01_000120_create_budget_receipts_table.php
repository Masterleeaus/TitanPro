<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('budget_receipts', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('company_id')->index();
            $table->unsignedBigInteger('expense_id')->nullable()->constrained('budget_expenses')->nullOnDelete();
            $table->string('file_path');
            $table->string('file_name');
            $table->string('mime_type');
            $table->enum('ocr_status', ['pending', 'processing', 'done', 'failed'])->default('pending');
            $table->json('ocr_data')->nullable();
            $table->decimal('extracted_amount', 15, 2)->nullable();
            $table->date('extracted_date')->nullable();
            $table->string('extracted_vendor')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('budget_receipts');
    }
};
