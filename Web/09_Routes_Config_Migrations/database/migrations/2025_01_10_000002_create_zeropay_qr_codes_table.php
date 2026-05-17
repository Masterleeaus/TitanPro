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
        Schema::create('zeropay_qr_codes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->index();
            $table->unsignedBigInteger('session_id')->index();
            $table->foreign('session_id')->references('id')->on('zeropay_sessions')->cascadeOnDelete();
            $table->string('payid')->nullable();
            $table->string('merchant_name');
            $table->decimal('amount', 18, 2)->nullable();
            $table->string('currency', 3)->default('AUD');
            $table->string('reference')->nullable();
            $table->string('session_token', 64)->index();
            $table->timestamp('expiry_timestamp');
            $table->string('fallback_url', 2048);
            $table->text('qr_payload_json');
            $table->string('status', 20)->default('active');
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
        Schema::dropIfExists('zeropay_qr_codes');
    }
};
