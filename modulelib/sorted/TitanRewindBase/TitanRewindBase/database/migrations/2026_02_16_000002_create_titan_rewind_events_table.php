<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('boxed_automation_events')) return;

        Schema::create('boxed_automation_events', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('company_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('case_id')->unsigned();

            $table->string('event_type', 80)->default('event');
            $table->string('entity_type', 120)->nullable();
            $table->bigInteger('entity_id')->nullable();

            $table->string('actor_type', 20)->default('system');
            $table->bigInteger('actor_id')->nullable();

            $table->string('idempotency_key', 100)->nullable();
            $table->json('payload_json')->nullable();

            $table->string('event_hash', 64)->nullable();
            $table->string('prev_event_hash', 64)->nullable();

            $table->timestamp('created_at');

            $table->index(['company_id','user_id','case_id']);
            $table->unique(['company_id','user_id','idempotency_key'], 'uniq_rewind_event_idem');
        });
    }

    public function down(): void
    {
        // non-destructive template
    }
};
