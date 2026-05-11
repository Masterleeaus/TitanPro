<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_theme_snapshots', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('organization_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->text('prompt');
            $table->json('tokens');
            $table->timestamps();

            $table->index(['organization_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_theme_snapshots');
    }
};
