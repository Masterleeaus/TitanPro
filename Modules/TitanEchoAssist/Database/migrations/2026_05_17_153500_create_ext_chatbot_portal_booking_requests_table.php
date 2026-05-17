<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('ext_chatbot_portal_booking_requests')) {
            return;
        }

        Schema::create('ext_chatbot_portal_booking_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('chatbot_id')->index();
            $table->unsignedBigInteger('conversation_id')->nullable()->index();
            $table->unsignedBigInteger('company_id')->nullable()->index();
            $table->unsignedBigInteger('customer_id')->nullable()->index();
            $table->date('requested_date')->nullable();
            $table->string('requested_time')->nullable();
            $table->string('service_type', 120);
            $table->text('notes')->nullable();
            $table->string('status', 40)->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ext_chatbot_portal_booking_requests');
    }
};
