<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('zeropay_transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('company_id')->index();
            $table->foreignId('session_id')->constrained('zeropay_sessions')->cascadeOnDelete();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('gateway');
            $table->string('gateway_reference')->nullable()->index();
            $table->decimal('amount', 12, 2);
            $table->string('currency', 3)->default('AUD');
            $table->string('status')->index();
            $table->decimal('fee', 8, 2)->default(0);
            $table->decimal('net_amount', 12, 2)->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['company_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('zeropay_transactions');
    }
};
