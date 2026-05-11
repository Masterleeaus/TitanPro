<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('training_content_modules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('audience')->nullable();
            $table->string('status')->default('draft');
            $table->unsignedInteger('duration_minutes')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();

            $table->index(['organization_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('training_content_modules');
    }
};
