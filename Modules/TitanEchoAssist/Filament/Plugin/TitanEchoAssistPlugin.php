<?php

namespace Modules\TitanEchoAssist\Filament\Plugin;

use Modules\TitanEchoAssist\Filament\Resources\ChatbotResource;
use Modules\TitanEchoAssist\Filament\Resources\ChannelResource;
use Modules\TitanEchoAssist\Filament\Resources\ConversationResource;
use Modules\TitanEchoAssist\Filament\Resources\KnowledgeResource;
use Modules\TitanEchoAssist\Filament\Widgets\ConversationStatsWidget;
use Modules\TitanEchoAssist\Filament\Widgets\UsageWidget;

if (interface_exists(\Filament\Contracts\Plugin::class)) {
    class TitanEchoAssistPlugin implements \Filament\Contracts\Plugin
    {
        public static function make(): static
        {
            return app(static::class);
        }

        public function getId(): string
        {
            return 'titan-chatbot';
        }

        public function register(\Filament\Panel $panel): void
        {
            $panel
                ->resources([
                    ChatbotResource::class,
                    ConversationResource::class,
                    KnowledgeResource::class,
                    ChannelResource::class,
                ])
                ->widgets([
                    ConversationStatsWidget::class,
                    UsageWidget::class,
                ]);
        }

        public function boot(\Filament\Panel $panel): void {}
    }
} else {
    class TitanEchoAssistPlugin
    {
        public static function make(): static
        {
            return new static();
        }

        public function getId(): string
        {
            return 'titan-chatbot';
        }
    }
}
