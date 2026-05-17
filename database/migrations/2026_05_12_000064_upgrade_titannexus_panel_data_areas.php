<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $this->createIfMissing('titan_nexus_campaigns', function (Blueprint $table) {
            $table->id(); $table->unsignedBigInteger('tenant_id')->nullable()->index(); $table->string('name')->nullable(); $table->string('vertical')->nullable()->index(); $table->string('status')->default('draft')->index(); $table->json('payload')->nullable(); $table->timestamps();
        });
        $this->createIfMissing('titan_nexus_leads', function (Blueprint $table) {
            $table->id(); $table->unsignedBigInteger('tenant_id')->nullable()->index(); $table->unsignedBigInteger('campaign_id')->nullable()->index(); $table->string('name')->nullable(); $table->string('email')->nullable()->index(); $table->string('phone')->nullable()->index(); $table->string('company')->nullable()->index(); $table->unsignedInteger('score')->default(0); $table->string('status')->default('new')->index(); $table->json('payload')->nullable(); $table->timestamps();
        });
        $this->createIfMissing('nexus_booking_handoffs', function (Blueprint $table) {
            $table->id(); $table->unsignedBigInteger('lead_id')->nullable()->index(); $table->string('status')->default('pending')->index(); $table->timestamp('scheduled_at')->nullable(); $table->json('payload')->nullable(); $table->timestamps();
        });
        $this->createIfMissing('nexus_training_contents', function (Blueprint $table) {
            $table->id(); $table->string('title'); $table->string('vertical')->nullable()->index(); $table->string('status')->default('draft')->index(); $table->longText('content')->nullable(); $table->json('payload')->nullable(); $table->timestamps();
        });
        $this->createIfMissing('nexus_contract_documents', function (Blueprint $table) {
            $table->id(); $table->string('title'); $table->string('vertical')->nullable()->index(); $table->string('status')->default('draft')->index(); $table->string('document_type')->nullable()->index(); $table->longText('content')->nullable(); $table->json('payload')->nullable(); $table->timestamps();
        });
        $this->createIfMissing('ext_contacts', function (Blueprint $table) {
            $table->id(); $table->string('name')->nullable(); $table->string('first_name')->nullable(); $table->string('last_name')->nullable(); $table->string('email')->nullable()->index(); $table->string('phone')->nullable()->index(); $table->string('company')->nullable()->index(); $table->string('status')->default('new')->index(); $table->json('payload')->nullable(); $table->timestamps();
        });
        $this->createIfMissing('ext_segments', function (Blueprint $table) {
            $table->id(); $table->string('name'); $table->text('description')->nullable(); $table->json('rules')->nullable(); $table->timestamps();
        });
        $this->createIfMissing('ext_contact_lists', function (Blueprint $table) {
            $table->id(); $table->string('name'); $table->text('description')->nullable(); $table->json('payload')->nullable(); $table->timestamps();
        });
        $this->createIfMissing('ext_marketing_campaigns', function (Blueprint $table) {
            $table->id(); $table->string('name')->nullable(); $table->string('title')->nullable(); $table->string('channel')->nullable()->index(); $table->string('status')->default('draft')->index(); $table->string('type')->nullable()->index(); $table->json('payload')->nullable(); $table->timestamps();
        });
        $this->createIfMissing('ext_marketing_conversations', function (Blueprint $table) {
            $table->id(); $table->unsignedBigInteger('contact_id')->nullable()->index(); $table->string('channel')->nullable()->index(); $table->string('status')->default('open')->index(); $table->timestamp('last_message_at')->nullable(); $table->json('payload')->nullable(); $table->timestamps();
        });
        $this->createIfMissing('ext_marketing_message_histories', function (Blueprint $table) {
            $table->id(); $table->string('direction')->nullable()->index(); $table->string('channel')->nullable()->index(); $table->string('status')->nullable()->index(); $table->longText('message')->nullable(); $table->json('payload')->nullable(); $table->timestamps();
        });
        $this->createIfMissing('ext_whatsapp_channels', function (Blueprint $table) {
            $table->id(); $table->string('name')->nullable(); $table->string('phone_number')->nullable(); $table->string('status')->default('inactive')->index(); $table->json('payload')->nullable(); $table->timestamps();
        });
        $this->createIfMissing('ext_telegram_bots', function (Blueprint $table) {
            $table->id(); $table->string('name')->nullable(); $table->string('status')->default('inactive')->index(); $table->json('payload')->nullable(); $table->timestamps();
        });
        $this->createIfMissing('ext_telegram_groups', function (Blueprint $table) {
            $table->id(); $table->string('name')->nullable(); $table->string('status')->default('inactive')->index(); $table->json('payload')->nullable(); $table->timestamps();
        });
    }

    private function createIfMissing(string $table, callable $callback): void
    {
        if (! Schema::hasTable($table)) { Schema::create($table, $callback); }
    }

    public function down(): void
    {
        foreach (['ext_telegram_groups','ext_telegram_bots','ext_whatsapp_channels','ext_marketing_message_histories','ext_marketing_conversations','ext_marketing_campaigns','ext_contact_lists','ext_segments','ext_contacts','nexus_contract_documents','nexus_training_contents','nexus_booking_handoffs','titan_nexus_leads','titan_nexus_campaigns'] as $table) { Schema::dropIfExists($table); }
    }
};
