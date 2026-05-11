<?php

use App\Extensions\TitanOperatorVoice\System\Enums\TrainTypeEnum;
use App\Extensions\TitanOperatorVoice\System\Models\ExtVoiceTitanOperator;
use App\Models\User;
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
        Schema::create('ext_titan_operator_voice_trains', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(ExtVoiceTitanOperator::class, 'operator_id')->constrained('ext_titan_operator_voice_bots')->cascadeOnDelete();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();

            $table->string('doc_id')->nullable();
            $table->string('name')->nullable();

            $table->enum('type', TrainTypeEnum::toArray());
            $table->string('file')->nullable();
            $table->text('url')->nullable();
            $table->text('text')->nullable();

            $table->datetime('trained_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ext_titan_operator_voice_bots');
    }
};
