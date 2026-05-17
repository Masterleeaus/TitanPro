<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('theme_packs', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->string('name', 120);
            $table->string('slug', 160);
            $table->text('description')->nullable();
            $table->json('tokens');
            $table->string('preview_image_path')->nullable();
            $table->json('tags')->nullable();
            $table->boolean('is_public')->default(false);
            $table->timestamps();

            $table->unique(['organization_id', 'slug']);
            $table->index(['organization_id', 'is_public']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('theme_packs');
    }
};
