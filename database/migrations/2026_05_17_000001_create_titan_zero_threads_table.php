<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('titan_zero_threads', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('organization_id')->index();
            $table->unsignedBigInteger('user_id')->index();
            $table->string('app_key', 80)->default('default');
            $table->string('title')->nullable();
            $table->json('messages')->nullable();
            $table->json('widgets')->nullable();
            $table->timestamps();

            $table->index(['organization_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('titan_zero_threads');
    }
};
