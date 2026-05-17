<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('ext_contacts')) {
            Schema::create('ext_contacts', function (Blueprint $table) {
                $table->id();
                $table->string('name')->nullable();
                $table->string('first_name')->nullable();
                $table->string('last_name')->nullable();
                $table->string('email')->nullable()->index();
                $table->string('phone')->nullable();
                $table->string('company')->nullable();
                $table->string('status')->default('new')->index();
                $table->json('meta')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('ext_segments')) {
            Schema::create('ext_segments', function (Blueprint $table) {
                $table->id();
                $table->string('name')->index();
                $table->text('description')->nullable();
                $table->json('filters')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('ext_contact_lists')) {
            Schema::create('ext_contact_lists', function (Blueprint $table) {
                $table->id();
                $table->string('name')->index();
                $table->text('description')->nullable();
                $table->json('meta')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('ext_contact_list_segment')) {
            Schema::create('ext_contact_list_segment', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('contact_list_id')->index();
                $table->unsignedBigInteger('segment_id')->index();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('ext_contact_list_contact')) {
            Schema::create('ext_contact_list_contact', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('contact_list_id')->index();
                $table->unsignedBigInteger('contact_id')->index();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('ext_marketing_campaigns')) {
            Schema::create('ext_marketing_campaigns', function (Blueprint $table) {
                $table->id();
                $table->string('name')->nullable()->index();
                $table->string('title')->nullable();
                $table->string('channel')->nullable()->index();
                $table->string('type')->nullable();
                $table->string('status')->default('draft')->index();
                $table->text('offer')->nullable();
                $table->text('message')->nullable();
                $table->json('settings')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('ext_marketing_conversations')) {
            Schema::create('ext_marketing_conversations', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('contact_id')->nullable()->index();
                $table->string('channel')->nullable()->index();
                $table->string('status')->default('open')->index();
                $table->timestamp('last_message_at')->nullable();
                $table->json('meta')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('ext_marketing_message_histories')) {
            Schema::create('ext_marketing_message_histories', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('conversation_id')->nullable()->index();
                $table->unsignedBigInteger('campaign_id')->nullable()->index();
                $table->string('direction')->default('outbound')->index();
                $table->string('channel')->nullable()->index();
                $table->string('status')->default('queued')->index();
                $table->text('message')->nullable();
                $table->json('payload')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('ext_whatsapp_channels')) {
            Schema::create('ext_whatsapp_channels', function (Blueprint $table) {
                $table->id();
                $table->string('name')->nullable();
                $table->string('phone_number')->nullable();
                $table->string('status')->default('inactive')->index();
                $table->json('settings')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('ext_telegram_bots')) {
            Schema::create('ext_telegram_bots', function (Blueprint $table) {
                $table->id();
                $table->string('name')->nullable();
                $table->string('username')->nullable();
                $table->string('status')->default('inactive')->index();
                $table->text('token')->nullable();
                $table->json('settings')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('ext_telegram_groups')) {
            Schema::create('ext_telegram_groups', function (Blueprint $table) {
                $table->id();
                $table->string('name')->nullable();
                $table->string('telegram_id')->nullable()->index();
                $table->boolean('is_admin')->default(false);
                $table->json('meta')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('ext_telegram_contacts')) {
            Schema::create('ext_telegram_contacts', function (Blueprint $table) {
                $table->id();
                $table->string('name')->nullable();
                $table->string('username')->nullable();
                $table->string('telegram_id')->nullable()->index();
                $table->json('meta')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('ext_telegram_group_subscribers')) {
            Schema::create('ext_telegram_group_subscribers', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('telegram_group_id')->nullable()->index();
                $table->unsignedBigInteger('telegram_contact_id')->nullable()->index();
                $table->string('status')->default('active')->index();
                $table->json('meta')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('nexus_training_contents')) {
            Schema::create('nexus_training_contents', function (Blueprint $table) {
                $table->id();
                $table->string('title')->index();
                $table->string('vertical')->nullable()->index();
                $table->string('status')->default('draft')->index();
                $table->longText('content')->nullable();
                $table->json('attachments')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('nexus_contract_documents')) {
            Schema::create('nexus_contract_documents', function (Blueprint $table) {
                $table->id();
                $table->string('title')->index();
                $table->string('vertical')->nullable()->index();
                $table->string('status')->default('draft')->index();
                $table->longText('body')->nullable();
                $table->json('meta')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        // Intentionally non-destructive. These tables may contain imported MarketingBot data.
    }
};
