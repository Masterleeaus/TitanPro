<?php

namespace Modules\TitanChatbot\Filament\Pages;

if (class_exists(\Filament\Pages\Page::class)) {
    class TitanChatbotPage extends \Filament\Pages\Page
    {
        protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';
        protected static string $view = 'titan-chatbot::filament.pages.titan-chatbot-page';
        protected static ?string $navigationLabel = 'Titan Chatbot';
        protected static ?string $slug = 'titan-chatbot';

        public function getViewData(): array
        {
            return ['module' => 'TitanChatbot'];
        }
    }
} else {
    class TitanChatbotPage
    {
        public function getViewData(): array
        {
            return ['module' => 'TitanChatbot'];
        }
    }
}
