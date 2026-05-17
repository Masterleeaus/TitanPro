<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('titantalk_handoffs')) {
            return;
        }

        Schema::create('titantalk_handoffs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('conversation_id')->index();
            $table->unsignedBigInteger('company_id')->nullable()->index();
            $table->string('channel', 30)->nullable()->index();
            $table->string('intent', 50)->nullable()->index();
            $table->string('state', 30)->default('open')->index();
            $table->string('priority', 20)->default('normal')->index();
            $table->string('goal', 120)->nullable();
            $table->text('reason')->nullable();
            $table->json('context')->nullable();
            $table->unsignedBigInteger('assigned_to_user_id')->nullable()->index();
            $table->timestamp('requested_at')->nullable()->index();
            $table->timestamp('assigned_at')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('titantalk_handoffs');
    }
};
