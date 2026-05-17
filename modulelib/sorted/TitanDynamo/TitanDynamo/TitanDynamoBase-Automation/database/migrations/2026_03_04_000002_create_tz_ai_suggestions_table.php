<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tz_ai_suggestions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('team_id');
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();

            $table->string('suggestion_type', 120)->default('generic');
            $table->tinyInteger('severity')->default(0);
            $table->string('title', 190);
            $table->text('body')->nullable();

            $table->string('subject_type', 120)->nullable();
            $table->unsignedBigInteger('subject_id')->nullable();

            $table->json('payload_json')->nullable();

            $table->string('status', 20)->default('open'); // open|accepted|dismissed
            $table->timestamp('resolved_at')->nullable();
            $table->unsignedBigInteger('resolved_by')->nullable();

            $table->timestamps();

            $table->index(['team_id', 'status', 'severity']);
            $table->index(['team_id', 'subject_type', 'subject_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tz_ai_suggestions');
    }
};
