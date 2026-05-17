<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ext_email_channels', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index();
            $table->string('provider')->default('smtp');
            $table->string('from_email')->nullable();
            $table->string('from_name')->nullable();
            // Optional provider config (kept nullable; can be driven by env if preferred)
            $table->string('smtp_host')->nullable();
            $table->integer('smtp_port')->nullable();
            $table->string('smtp_username')->nullable();
            $table->string('smtp_password')->nullable();
            $table->string('smtp_encryption')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('ext_outbox_drafts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('conversation_id')->nullable()->index();
            $table->string('channel')->index(); // sms|whatsapp|telegram|email|voice
            $table->string('to')->nullable();
            $table->string('subject')->nullable();
            $table->longText('body')->nullable();
            $table->json('payload')->nullable();
            $table->string('status')->default('draft')->index(); // draft|pending_approval|approved|sent|failed|cancelled
            $table->boolean('is_ai_generated')->default(false);
            $table->boolean('requires_approval')->default(false);
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->string('last_error')->nullable();
            $table->timestamps();
        });

        Schema::create('ext_outbox_approvals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('draft_id')->index();
            $table->string('approver')->default('titan_zero');
            $table->string('approval_status')->default('pending')->index(); // pending|approved|rejected
            $table->string('approval_token')->nullable()->index();
            $table->json('approval_payload')->nullable();
            $table->timestamp('decided_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ext_outbox_approvals');
        Schema::dropIfExists('ext_outbox_drafts');
        Schema::dropIfExists('ext_email_channels');
    }
};
