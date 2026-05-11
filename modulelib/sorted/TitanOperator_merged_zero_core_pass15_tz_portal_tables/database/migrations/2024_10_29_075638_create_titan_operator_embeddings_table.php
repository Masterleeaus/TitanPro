<?php

use App\Extensions\TitanOperator\System\Enums\EmbeddingTypeEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public static $prefix = 'tz_portal';

    public function up(): void
    {
        if (Schema::hasTable('tz_portal_operator_embeddings')) {
            return;
        }
        Schema::create(self::$prefix . '_operator_embeddings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('operator_id')->constrained(self::$prefix . '_operator_bots')->cascadeOnDelete();
            $table->string('engine');
            $table->string('title')->nullable();
            $table->string('file')->nullable();
            $table->string('url')->nullable();
            $table->longText('content')->nullable();
            $table->json('embedding')->nullable();
            $table->string('type')->nullable()->default(EmbeddingTypeEnum::text->value);
            $table->timestamp('trained_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(self::$prefix . '_operator_embeddings');
    }
};
