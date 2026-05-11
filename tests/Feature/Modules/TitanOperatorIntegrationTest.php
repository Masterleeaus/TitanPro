<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Modules\TitanEchoAssist\Models\Conversation as EchoConversation;
use Modules\TitanOperator\Models\KnowledgeBaseArticle;
use Modules\TitanOperator\Providers\TitanOperatorServiceProvider;

uses(RefreshDatabase::class);

beforeEach(function () {
    app()->register(TitanOperatorServiceProvider::class);

    if (! Schema::hasTable('tz_portal_operator_channels')) {
        Schema::create('tz_portal_operator_channels', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('operator_id')->nullable();
            $table->timestamps();
        });
    }

    if (! Schema::hasTable('tz_portal_operator_conversations')) {
        Schema::create('tz_portal_operator_conversations', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('operator_id');
            $table->string('session_id');
            $table->unsignedBigInteger('operator_channel_id')->nullable();
            $table->timestamp('last_activity_at')->nullable();
            $table->timestamps();
        });
    }

    if (! Schema::hasTable('ext_chatbot_conversations')) {
        Schema::create('ext_chatbot_conversations', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('chatbot_id')->nullable();
            $table->string('session_id')->nullable();
            $table->unsignedBigInteger('chatbot_channel_id')->nullable();
            $table->timestamp('last_activity_at')->nullable();
            $table->timestamps();
        });
    }

    if (! Schema::hasTable('tz_portal_operator_knowledge_base_articles')) {
        Schema::create('tz_portal_operator_knowledge_base_articles', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->text('content')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->json('operators')->nullable();
            $table->timestamps();
        });
    }

    if (! Schema::hasTable('tz_portal_operator_embeddings')) {
        Schema::create('tz_portal_operator_embeddings', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('operator_id');
            $table->string('engine');
            $table->string('title')->nullable();
            $table->longText('content')->nullable();
            $table->string('type')->nullable();
            $table->timestamp('trained_at')->nullable();
            $table->timestamps();
        });
    }
});

test('TitanOperator module manifest is active on titanpro panel', function () {
    $manifest = json_decode(file_get_contents(base_path('Modules/TitanOperator/module.json')), true, 512, JSON_THROW_ON_ERROR);

    expect($manifest['active'])->toBe(1)
        ->and($manifest['filament_panel'])->toBe('titanpro')
        ->and($manifest['providers'])->toContain('Modules\\TitanOperator\\Providers\\TitanOperatorServiceProvider');
});

test('webhook dispatch route creates operator conversation', function () {
    $response = $this->postJson('/api/v2/titan_operator/33/channel/8/whatsapp', [
        'session_id' => 'abc-123',
    ]);

    $response->assertOk()->assertJsonPath('operator_id', 33);

    $this->assertDatabaseHas('tz_portal_operator_conversations', [
        'operator_id' => 33,
        'session_id' => 'abc-123',
        'operator_channel_id' => 8,
    ]);
});

test('knowledge base article save triggers embedding ingest', function () {
    KnowledgeBaseArticle::query()->create([
        'title' => 'FAQ',
        'content' => 'Answer text',
        'operators' => [77],
    ]);

    $this->assertDatabaseHas('tz_portal_operator_embeddings', [
        'operator_id' => 77,
        'title' => 'FAQ',
        'engine' => 'titanzero-vector',
        'type' => 'text',
    ]);
});

test('echoassist conversations are associated to operator by channel', function () {
    \DB::table('tz_portal_operator_channels')->insert([
        'id' => 9,
        'operator_id' => 45,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    EchoConversation::query()->create([
        'chatbot_channel_id' => 9,
        'session_id' => 'sess-999',
        'last_activity_at' => now(),
    ]);

    $this->assertDatabaseHas('tz_portal_operator_conversations', [
        'operator_id' => 45,
        'session_id' => 'sess-999',
        'operator_channel_id' => 9,
    ]);
});
