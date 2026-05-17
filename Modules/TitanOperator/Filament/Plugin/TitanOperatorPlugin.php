<?php

namespace Modules\TitanOperator\Filament\Plugin;

use Modules\TitanOperator\Filament\Resources\ChannelResource;
use Modules\TitanOperator\Filament\Resources\ConversationResource;
use Modules\TitanOperator\Filament\Resources\KnowledgeBaseResource;
use Modules\TitanOperator\Filament\Resources\OperatorResource;

if (interface_exists(\Filament\Contracts\Plugin::class)) {
    class TitanOperatorPlugin implements \Filament\Contracts\Plugin
    {
        public static function make(): static
        {
            return app(static::class);
        }

        public function getId(): string
        {
            return 'titan-operator';
        }

        public function register(\Filament\Panel $panel): void
        {
            $panel->resources([
                OperatorResource::class,
                ChannelResource::class,
                KnowledgeBaseResource::class,
                ConversationResource::class,
            ]);
        }

        public function boot(\Filament\Panel $panel): void {}
    }
} else {
    class TitanOperatorPlugin
    {
        public static function make(): static
        {
            return new static;
        }

        public function getId(): string
        {
            return 'titan-operator';
        }
    }
}
