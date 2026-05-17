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
        Schema::create('zeropay_sessions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->index();       // tenant scope
            $table->unsignedBigInteger('user_id')->index();           // session owner (worker/merchant)
            $table->string('session_token', 64)->unique();
            $table->string('payid')->nullable();
            $table->string('merchant_name');
            $table->decimal('amount', 18, 2)->nullable();             // null = any amount
            $table->string('currency', 3)->default('AUD');
            $table->string('reference')->nullable();
            $table->string('gateway')->nullable();                    // PayID|BankTransfer|Stripe|etc
            $table->string('status', 32)->default('pending');        // pending|opened|processing|completed|failed|expired
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('opened_at')->nullable();
            $table->timestamp('completed_at')->nullable();
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
        Schema::dropIfExists('zeropay_sessions');
    }
};
