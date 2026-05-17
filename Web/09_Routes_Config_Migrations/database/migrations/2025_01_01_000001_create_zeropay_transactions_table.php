<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('zeropay_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->index();
            $table->unsignedBigInteger('session_id')->nullable();
            $table->foreign('session_id')->references('id')->on('zeropay_sessions')->nullOnDelete();
            $table->string('type', 32);                               // payment|refund|fee
            $table->string('gateway', 32);
            $table->string('gateway_reference')->nullable();
            $table->decimal('amount', 18, 2);
            $table->decimal('fee', 18, 2)->default(0);
            $table->string('currency', 3)->default('AUD');
            $table->string('status', 32)->default('pending');        // pending|completed|failed|refunded
            $table->unsignedBigInteger('payer_user_id')->nullable();
            $table->string('payer_name')->nullable();
            $table->unsignedBigInteger('payee_user_id')->nullable();
            $table->string('payee_name')->nullable();
            $table->string('reference')->nullable();
            $table->text('failure_reason')->nullable();
            $table->json('gateway_response')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('zeropay_transactions');
    }
};
