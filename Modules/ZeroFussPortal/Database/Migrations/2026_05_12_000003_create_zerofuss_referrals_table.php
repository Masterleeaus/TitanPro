<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('zerofuss_referrals', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('customer_id');
            $table->string('referral_code')->unique();
            $table->string('referred_email');
            $table->string('referred_name')->nullable();
            $table->string('status', 30)->default('pending');
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['company_id', 'customer_id']);
            $table->index(['company_id', 'referred_email']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('zerofuss_referrals');
    }
};
