<?php

namespace Modules\TitanEchoAssist\Filament\Pages;

if (class_exists(\Filament\Pages\Page::class)) {
    class TitanChatbotDashboard extends \Filament\Pages\Page
    {
        protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-command-line';
        protected string $view = 'titan-chatbot::filament.pages.titan-chatbot-dashboard';
        protected static ?string $navigationLabel = 'Chatbot Command Center';
        protected static ?string $slug = 'titan-chatbot-dashboard';

        public function getViewData(): array
        {
            return ['module' => 'TitanChatbot', 'surface' => 'dashboard'];
        }
    }
} else {
    class TitanChatbotDashboard
    {
        public function getViewData(): array
        {
            return ['module' => 'TitanChatbot', 'surface' => 'dashboard'];
        }
    }
}
