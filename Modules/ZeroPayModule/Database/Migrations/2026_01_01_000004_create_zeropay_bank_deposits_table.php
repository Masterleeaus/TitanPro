<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('zeropay_bank_deposits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->index();
            $table->string('reference')->nullable()->index();
            $table->decimal('amount', 18, 2);
            $table->string('currency', 3)->default('AUD');
            $table->string('depositor_name')->nullable();
            $table->string('depositor_account')->nullable();
            $table->timestamp('deposited_at')->nullable();
            $table->unsignedBigInteger('matched_transaction_id')->nullable();
            $table->string('status')->default('pending_review');
            $table->json('raw_data')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('zeropay_bank_deposits');
    }
};
