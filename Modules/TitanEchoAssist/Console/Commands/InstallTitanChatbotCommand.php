<?php

namespace Modules\TitanEchoAssist\Console\Commands;

use Illuminate\Console\Command;

class InstallTitanChatbotCommand extends Command
{
    protected $signature = 'titan-chatbot:install';

    protected $description = 'Install TitanChatbot module assets and contracts.';

    public function handle(): int
    {
        return self::SUCCESS;
    }
}
