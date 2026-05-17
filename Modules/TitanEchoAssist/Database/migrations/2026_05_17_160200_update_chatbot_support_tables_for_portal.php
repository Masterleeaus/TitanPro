<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('ext_chatbot_avatars')) {
            Schema::table('ext_chatbot_avatars', function (Blueprint $table) {
                if (! Schema::hasColumn('ext_chatbot_avatars', 'company_id')) {
                    $table->unsignedBigInteger('company_id')->nullable()->index()->after('id');
                }
                if (! Schema::hasColumn('ext_chatbot_avatars', 'chatbot_id')) {
                    $table->unsignedBigInteger('chatbot_id')->nullable()->index()->after('company_id');
                }
                if (! Schema::hasColumn('ext_chatbot_avatars', 'file_path')) {
                    $table->string('file_path')->nullable()->after('chatbot_id');
                }
                if (! Schema::hasColumn('ext_chatbot_avatars', 'file_name')) {
                    $table->string('file_name')->nullable()->after('file_path');
                }
                if (! Schema::hasColumn('ext_chatbot_avatars', 'mime_type')) {
                    $table->string('mime_type')->nullable()->after('file_name');
                }
                if (! Schema::hasColumn('ext_chatbot_avatars', 'size')) {
                    $table->unsignedBigInteger('size')->nullable()->after('mime_type');
                }
                if (! Schema::hasColumn('ext_chatbot_avatars', 'is_default')) {
                    $table->boolean('is_default')->default(false)->after('size');
                }
            });
        }

        if (! Schema::hasTable('ext_chatbot_canned_responses')) {
            Schema::create('ext_chatbot_canned_responses', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('company_id')->nullable()->index();
                $table->unsignedBigInteger('chatbot_id')->nullable()->index();
                $table->string('title');
                $table->text('content');
                $table->boolean('is_portal_friendly')->default(true);
                $table->unsignedInteger('sort_order')->default(0);
                $table->timestamps();
            });
        }

        if (Schema::hasTable('ext_chatbot_channel_webhooks')) {
            Schema::table('ext_chatbot_channel_webhooks', function (Blueprint $table) {
                if (! Schema::hasColumn('ext_chatbot_channel_webhooks', 'company_id')) {
                    $table->unsignedBigInteger('company_id')->nullable()->index()->after('id');
                }
                if (! Schema::hasColumn('ext_chatbot_channel_webhooks', 'channel_id')) {
                    $table->unsignedBigInteger('channel_id')->nullable()->index()->after('chatbot_id');
                }
                if (! Schema::hasColumn('ext_chatbot_channel_webhooks', 'provider')) {
                    $table->string('provider')->nullable()->after('channel_id');
                }
                if (! Schema::hasColumn('ext_chatbot_channel_webhooks', 'webhook_url')) {
                    $table->string('webhook_url')->nullable()->after('provider');
                }
                if (! Schema::hasColumn('ext_chatbot_channel_webhooks', 'verify_token')) {
                    $table->string('verify_token')->nullable()->after('webhook_url');
                }
                if (! Schema::hasColumn('ext_chatbot_channel_webhooks', 'secret')) {
                    $table->string('secret')->nullable()->after('verify_token');
                }
                if (! Schema::hasColumn('ext_chatbot_channel_webhooks', 'is_active')) {
                    $table->boolean('is_active')->default(true)->after('secret');
                }
                if (! Schema::hasColumn('ext_chatbot_channel_webhooks', 'last_received_at')) {
                    $table->dateTime('last_received_at')->nullable()->after('is_active');
                }
                if (! Schema::hasColumn('ext_chatbot_channel_webhooks', 'updated_at')) {
                    $table->timestamp('updated_at')->nullable()->useCurrent()->after('created_at');
                }
            });
        }

        if (Schema::hasTable('ext_chatbot_channel_webhooks') && Schema::hasTable('ext_chatbot_channels')) {
            Schema::table('ext_chatbot_channel_webhooks', function (Blueprint $table) {
                $table->foreign('channel_id')
                    ->references('id')
                    ->on('ext_chatbot_channels')
                    ->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('ext_chatbot_canned_responses');
    }
};
