<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fsm_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->unique();
            $table->string('vertical', 50)->default('general_trades');
            $table->json('features')->nullable()->comment('per-tenant feature overrides');
            $table->json('terminology')->nullable()->comment('custom label overrides');
            $table->json('branding')->nullable()->comment('pdf_header, pdf_footer, logo_path');
            $table->string('webhook_url')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fsm_settings');
    }
};
