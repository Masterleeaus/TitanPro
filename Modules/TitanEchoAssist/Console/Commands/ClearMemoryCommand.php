<?php

namespace Modules\TitanEchoAssist\Console\Commands;

use Illuminate\Console\Command;
use Modules\TitanEchoAssist\AI\Memory\ConversationMemoryStore;

class ClearMemoryCommand extends Command
{
    protected $signature   = 'titan-chatbot:clear-memory {session? : Session ID to clear (omit to clear all)}';
    protected $description = 'Clear TitanChatbot conversation memory from cache';

    public function handle(): int
    {
        $sessionId = $this->argument('session');

        if ($sessionId) {
            app(ConversationMemoryStore::class)->forget($sessionId);
            $this->info("Memory cleared for session: {$sessionId}");
        } else {
            if (class_exists(\Modules\TitanEchoAssist\AI\Memory\Drivers\InMemoryMemoryDriver::class)) {
                \Modules\TitanEchoAssist\AI\Memory\Drivers\InMemoryMemoryDriver::flush();
            }
            $this->info('In-memory conversation store cleared. Cache-backed sessions expire automatically.');
        }

        return Command::SUCCESS;
    }
}
