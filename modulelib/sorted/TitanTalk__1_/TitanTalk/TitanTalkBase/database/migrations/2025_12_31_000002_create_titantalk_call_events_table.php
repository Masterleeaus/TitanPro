<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('titantalk_call_events', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('call_id')->index();
            $table->string('event_type', 32)->index();
            $table->string('event_name', 64)->index();
            $table->json('payload')->nullable();
            $table->timestamp('occurred_at')->nullable();
            $table->timestamps();

            $table->foreign('call_id')->references('id')->on('titantalk_calls')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('titantalk_call_events');
    }
};
