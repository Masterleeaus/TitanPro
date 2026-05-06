<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('booking_lifecycle_logs')) {
            return;
        }

        Schema::create('booking_lifecycle_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable()->index();
            $table->string('subject_type', 160)->index();
            $table->unsignedBigInteger('subject_id')->index();
            $table->string('event', 80)->index();
            $table->string('from_status', 80)->nullable();
            $table->string('to_status', 80)->nullable();
            $table->unsignedBigInteger('actor_id')->nullable()->index();
            $table->json('payload')->nullable();
            $table->timestamp('occurred_at')->nullable()->index();
            $table->timestamps();

            $table->index(['subject_type', 'subject_id', 'event'], 'booking_lifecycle_subject_event_idx');
            $table->index(['company_id', 'event', 'occurred_at'], 'booking_lifecycle_company_event_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_lifecycle_logs');
    }
};
