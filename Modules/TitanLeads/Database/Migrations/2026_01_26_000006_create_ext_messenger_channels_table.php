<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ext_messenger_channels', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->index();
            $table->string('provider')->default('meta'); // meta = Facebook/Instagram Messaging
            $table->string('page_id')->nullable();
            $table->string('access_token')->nullable();
            $table->string('verify_token')->nullable(); // for webhook verification
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ext_messenger_channels');
    }
};
