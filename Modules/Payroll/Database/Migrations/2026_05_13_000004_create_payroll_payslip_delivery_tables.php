<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payroll_payslip_deliveries', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('salary_slip_id')->nullable()->index();
            $table->string('recipient')->nullable();
            $table->json('channels')->nullable();
            $table->string('status')->default('queued')->index();
            $table->text('error')->nullable();
            $table->json('meta')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('acknowledged_at')->nullable();
            $table->timestamps();
        });

        Schema::create('payroll_payslip_acknowledgements', function (Blueprint $table) {
            $table->id();
            $table->uuid('delivery_uuid')->index();
            $table->unsignedBigInteger('user_id')->index();
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->json('meta')->nullable();
            $table->timestamp('acknowledged_at');
            $table->timestamps();
            $table->unique(['delivery_uuid', 'user_id']);
        });

        Schema::create('payroll_payslip_delivery_preferences', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unique();
            $table->json('preferences')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payroll_payslip_delivery_preferences');
        Schema::dropIfExists('payroll_payslip_acknowledgements');
        Schema::dropIfExists('payroll_payslip_deliveries');
    }
};
