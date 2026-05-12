<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('calling_agent_configs')) {
            return;
        }

        Schema::create('calling_agent_configs', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('company_id')->index();
            $table->string('twilio_account_sid')->nullable();
            $table->text('twilio_auth_token')->nullable();
            $table->string('twilio_from_number')->nullable();
            $table->string('twilio_whatsapp_from')->nullable();
            $table->text('elevenlabs_api_key')->nullable();
            $table->string('sip_username')->nullable();
            $table->text('sip_password')->nullable();
            $table->string('sip_domain')->nullable();
            $table->timestamps();

            $table->unique('company_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('calling_agent_configs');
    }
};
