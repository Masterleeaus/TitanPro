<?php

use App\Models\Organization;
use App\Models\TitanZeroThread;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;

// ── Helpers ──────────────────────────────────────────────────────────────────

function tzSetup(): array
{
    (new RolesAndPermissionsSeeder)->run();

    $org  = Organization::factory()->create();
    $user = User::factory()->create(['organization_id' => $org->id]);

    return [$user, $org];
}

// ── POST /api/titan/zero/generate-ui ─────────────────────────────────────────

test('generate-ui returns 401 for unauthenticated requests', function () {
    $this->postJson('/api/titan/zero/generate-ui', ['message' => 'hello'])
        ->assertUnauthorized();
});

test('generate-ui returns 422 when message is missing', function () {
    [$user] = tzSetup();

    $this->actingAs($user)
        ->postJson('/api/titan/zero/generate-ui', [])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['message']);
});

test('generate-ui returns valid AgentUiResponse shape', function () {
    [$user] = tzSetup();

    $this->actingAs($user)
        ->postJson('/api/titan/zero/generate-ui', [
            'message' => 'Show me today\'s jobs',
            'context' => ['appKey' => 'owner', 'page' => '/owner/jobs'],
        ])
        ->assertOk()
        ->assertJsonStructure([
            'is_task_complete',
            'message',
            'parts',
            'errors',
            'meta' => ['threadId', 'suggestions'],
        ])
        ->assertJson(['is_task_complete' => true])
        ->assertJsonCount(0, 'errors');
});

test('generate-ui creates a thread persisted to the database', function () {
    [$user, $org] = tzSetup();

    $response = $this->actingAs($user)
        ->postJson('/api/titan/zero/generate-ui', [
            'message' => 'What is my revenue this week?',
            'context' => ['appKey' => 'owner'],
        ])
        ->assertOk();

    $threadId = $response->json('meta.threadId');
    expect($threadId)->not->toBeNull();

    $thread = TitanZeroThread::find($threadId);
    expect($thread)->not->toBeNull();
    expect($thread->organization_id)->toBe($org->id);
    expect($thread->user_id)->toBe($user->id);

    $messages = $thread->messages ?? [];
    expect($messages)->not->toBeEmpty();
    expect($messages[0]['role'])->toBe('user');
    expect($messages[0]['content'])->toBe('What is my revenue this week?');
});

test('generate-ui continues an existing thread when threadId is supplied', function () {
    [$user, $org] = tzSetup();

    // First message — creates a thread
    $first = $this->actingAs($user)
        ->postJson('/api/titan/zero/generate-ui', [
            'message' => 'Hello',
            'context' => ['appKey' => 'owner'],
        ])
        ->assertOk();

    $threadId = $first->json('meta.threadId');
    expect($threadId)->not->toBeNull();

    // Second message — continues the same thread
    $this->actingAs($user)
        ->postJson('/api/titan/zero/generate-ui', [
            'message'  => 'Tell me more',
            'threadId' => (int) $threadId,
            'context'  => ['appKey' => 'owner'],
        ])
        ->assertOk()
        ->assertJsonPath('meta.threadId', $threadId);

    $thread = TitanZeroThread::find($threadId);
    // Should have 4 messages: user + assistant + user + assistant
    expect(count($thread->messages ?? []))->toBe(4);
});

test('generate-ui parts array contains at least one widget', function () {
    [$user] = tzSetup();

    $response = $this->actingAs($user)
        ->postJson('/api/titan/zero/generate-ui', [
            'message' => 'Status check',
        ])
        ->assertOk();

    $parts = $response->json('parts');
    expect($parts)->toBeArray();
    expect(count($parts))->toBeGreaterThan(0);

    $first = $parts[0];
    expect($first)->toHaveKey('id');
    expect($first)->toHaveKey('kind');
});

// ── GET /api/titan/threads/{threadId} ────────────────────────────────────────

test('thread show returns 401 for unauthenticated requests', function () {
    $this->getJson('/api/titan/threads/999')
        ->assertUnauthorized();
});

test('thread show returns 404 for non-existent thread', function () {
    [$user] = tzSetup();

    $this->actingAs($user)
        ->getJson('/api/titan/threads/99999999')
        ->assertNotFound();
});

test('thread show returns messages and widgets', function () {
    [$user, $org] = tzSetup();

    $thread = TitanZeroThread::create([
        'organization_id' => $org->id,
        'user_id'         => $user->id,
        'app_key'         => 'owner',
        'title'           => 'Test thread',
        'messages'        => [
            ['id' => 'a', 'role' => 'user',      'content' => 'Hello',  'createdAt' => now()->toISOString()],
            ['id' => 'b', 'role' => 'assistant',  'content' => 'Hi!',    'createdAt' => now()->toISOString()],
        ],
        'widgets' => [
            ['id' => 'w-1', 'kind' => 'metric-card', 'title' => 'Jobs', 'data' => ['value' => 3]],
        ],
    ]);

    $this->actingAs($user)
        ->getJson("/api/titan/threads/{$thread->id}")
        ->assertOk()
        ->assertJsonStructure(['messages', 'widgets'])
        ->assertJsonCount(2, 'messages')
        ->assertJsonCount(1, 'widgets');
});

test('thread show enforces tenant isolation', function () {
    [$user]         = tzSetup();
    [$otherUser, $otherOrg] = tzSetup();

    $thread = TitanZeroThread::create([
        'organization_id' => $otherOrg->id,
        'user_id'         => $otherUser->id,
        'app_key'         => 'owner',
        'title'           => 'Other org thread',
        'messages'        => [],
        'widgets'         => [],
    ]);

    // $user belongs to a different org — should get an authorization failure
    $this->actingAs($user)
        ->getJson("/api/titan/threads/{$thread->id}")
        ->assertForbidden();
});

// ── GET /api/titan/suggestions ───────────────────────────────────────────────

test('suggestions returns 401 for unauthenticated requests', function () {
    $this->getJson('/api/titan/suggestions')
        ->assertUnauthorized();
});

test('suggestions returns an array of strings', function () {
    [$user] = tzSetup();

    $response = $this->actingAs($user)
        ->getJson('/api/titan/suggestions')
        ->assertOk()
        ->assertJsonStructure(['suggestions']);

    $suggestions = $response->json('suggestions');
    expect($suggestions)->toBeArray();
    expect(count($suggestions))->toBeGreaterThanOrEqual(3);
    expect(count($suggestions))->toBeLessThanOrEqual(5);
});

test('suggestions returns owner-specific chips for owner appKey', function () {
    [$user] = tzSetup();

    $response = $this->actingAs($user)
        ->getJson('/api/titan/suggestions?appKey=owner')
        ->assertOk();

    $suggestions = $response->json('suggestions');
    expect($suggestions)->toContain('Show jobs today');
});

test('suggestions use thread context when threadId is provided', function () {
    [$user, $org] = tzSetup();

    $thread = TitanZeroThread::create([
        'organization_id' => $org->id,
        'user_id'         => $user->id,
        'app_key'         => 'owner',
        'title'           => 'Owner thread',
        'messages'        => [],
        'widgets'         => [],
    ]);

    $response = $this->actingAs($user)
        ->getJson("/api/titan/suggestions?threadId={$thread->id}")
        ->assertOk();

    $suggestions = $response->json('suggestions');
    expect($suggestions)->toContain('Show jobs today');
});

test('suggestions returns default chips for unknown appKey', function () {
    [$user] = tzSetup();

    $response = $this->actingAs($user)
        ->getJson('/api/titan/suggestions?appKey=unknown_app')
        ->assertOk();

    $suggestions = $response->json('suggestions');
    expect($suggestions)->toBe([
        'Open app',
        'Search workspace',
        'Explain this screen',
        'Show recent activity',
        'Help me navigate',
    ]);
});
