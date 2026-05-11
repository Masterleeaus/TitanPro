<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('share_links')) {
            Schema::create('share_links', function (Blueprint $table) {
                $table->id();
                $table->string('url')->unique();
                $table->unsignedBigInteger('category')->nullable();
                $table->unsignedBigInteger('chat')->nullable();
                $table->unsignedBigInteger('message')->nullable();
                $table->timestamp('time')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('share_links');
    }
};
