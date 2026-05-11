<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tz_pending_actions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('team_id');
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();

            $table->string('action_type', 120); // send_invoice, schedule_dispatch, etc
            $table->string('title', 190);
            $table->text('body')->nullable();

            $table->string('subject_type', 120)->nullable();
            $table->unsignedBigInteger('subject_id')->nullable();

            $table->json('payload_json')->nullable();

            $table->string('status', 20)->default('pending'); // pending|approved|rejected|executed|cancelled
            $table->timestamp('decided_at')->nullable();
            $table->unsignedBigInteger('decided_by')->nullable();

            $table->timestamps();

            $table->index(['team_id', 'status']);
            $table->index(['team_id', 'action_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tz_pending_actions');
    }
};
