<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shared_themes', function (Blueprint $table): void {
            $table->id();
            $table->string('token', 64)->unique()->index();
            $table->string('name', 120);
            $table->string('author', 120)->nullable();
            $table->json('tokens');      // theme token values
            $table->unsignedSmallInteger('views')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shared_themes');
    }
};
