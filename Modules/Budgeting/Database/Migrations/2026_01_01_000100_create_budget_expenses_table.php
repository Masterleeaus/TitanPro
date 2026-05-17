<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('budget_expenses', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('company_id')->index();
            $table->unsignedBigInteger('submitted_by')->nullable()->constrained('users')->nullOnDelete();
            $table->unsignedBigInteger('category_id')->nullable()->index();
            $table->decimal('amount', 15, 2);
            $table->char('currency', 3)->default('AUD');
            $table->text('description')->nullable();
            $table->enum('status', ['draft', 'pending', 'approved', 'rejected', 'reimbursed'])->default('draft');
            $table->unsignedBigInteger('receipt_id')->nullable()->index();
            $table->unsignedBigInteger('budget_allocation_id')->nullable()->index();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('budget_expenses');
    }
};
