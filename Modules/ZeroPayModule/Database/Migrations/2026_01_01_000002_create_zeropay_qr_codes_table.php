<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('zeropay_qr_codes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('company_id')->index();
            $table->foreignId('session_id')->constrained('zeropay_sessions')->cascadeOnDelete();
            $table->string('pay_id');
            $table->string('merchant_name');
            $table->decimal('amount', 12, 2)->nullable();
            $table->string('currency', 3)->default('AUD');
            $table->string('reference');
            $table->string('session_token')->index();
            $table->json('payload');
            $table->timestamp('expiry_timestamp')->nullable();
            $table->string('qr_image_path')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('zeropay_qr_codes');
    }
};
