<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tz_analyses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('team_id');
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();

            $table->string('analysis_type', 120);
            $table->string('title', 190);
            $table->text('summary')->nullable();
            $table->json('result_json')->nullable();

            $table->timestamps();

            $table->index(['team_id', 'analysis_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tz_analyses');
    }
};
