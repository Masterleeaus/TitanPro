<?php

use App\Extensions\TitanOperatorVoice\System\Enums\RoleEnum;
use App\Extensions\TitanOperatorVoice\System\Models\ExtVoicechabotConversation;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ext_titan_operator_voice_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(ExtVoicechabotConversation::class, 'conversation_id')
                ->constrained('ext_voicechabot_conversations')
                ->cascadeOnDelete();

            $table->enum('role', RoleEnum::toArray());
            $table->text('message');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ext_titan_operator_voice_histories');
    }
};
