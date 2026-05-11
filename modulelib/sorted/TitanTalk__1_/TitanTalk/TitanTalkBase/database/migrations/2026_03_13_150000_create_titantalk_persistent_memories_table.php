<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('titantalk_persistent_memories')) {
            return;
        }

        Schema::create('titantalk_persistent_memories', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('conversation_id')->index();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('channel', 50)->nullable()->index();
            $table->string('memory_key', 120)->index();
            $table->json('memory_value')->nullable();
            $table->timestamp('last_seen_at')->nullable()->index();
            $table->timestamps();

            $table->unique(['conversation_id', 'memory_key'], 'titantalk_memories_conversation_key_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('titantalk_persistent_memories');
    }
};
