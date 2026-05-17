<?php

use Modules\TitanEchoAssist\Services\ConversationRouter;
use Modules\TitanTalk\Models\Conversation;
use Modules\TitanTalk\Models\Message;

test('inbound whatsapp webhook messages are threaded in titantalk inbox', function () {
    $router = Mockery::mock(ConversationRouter::class);
    $router->shouldReceive('route')->twice()->andReturn('Automated response');
    app()->instance(ConversationRouter::class, $router);

    $this->post('/webhooks/titan-chatbot/whatsapp/1', [
        'WaId' => '61400000000',
        'Body' => 'First message',
    ])->assertOk();

    $this->post('/webhooks/titan-chatbot/whatsapp/1', [
        'WaId' => '61400000000',
        'Body' => 'Second message',
    ])->assertOk();

    expect(Conversation::query()->where('channel', 'whatsapp')->count())->toBe(1);
    expect(Message::query()->whereHas('conversation', fn ($q) => $q->where('channel', 'whatsapp'))->count())->toBe(4);
});

test('conversation threading is scoped by contact and channel', function () {
    $router = Mockery::mock(ConversationRouter::class);
    $router->shouldReceive('route')->twice()->andReturn('Automated response');
    app()->instance(ConversationRouter::class, $router);

    $this->post('/webhooks/titan-chatbot/whatsapp/1', [
        'WaId' => 'same-user',
        'Body' => 'WhatsApp ping',
    ])->assertOk();

    $this->post('/webhooks/titan-chatbot/telegram/1', [
        'message' => [
            'from' => ['id' => 'same-user'],
            'text' => 'Telegram ping',
        ],
    ])->assertOk();

    expect(Conversation::query()->count())->toBe(2)
        ->and(Conversation::query()->where('channel', 'whatsapp')->count())->toBe(1)
        ->and(Conversation::query()->where('channel', 'telegram')->count())->toBe(1);
});

