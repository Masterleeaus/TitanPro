<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ext_titan_operator_knowledge_base_articles', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->nullable();
            $table->string('title', 500)->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->json('operators')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ext_titan_operator_knowledge_base_articles');
    }
};
