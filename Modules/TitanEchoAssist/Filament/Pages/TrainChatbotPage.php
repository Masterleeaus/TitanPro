<?php

namespace Modules\TitanEchoAssist\Filament\Pages;

use Modules\TitanEchoAssist\Models\Chatbot;
use Modules\TitanEchoAssist\Services\TrainingPipeline;

if (class_exists(\Filament\Pages\Page::class)) {
    class TrainChatbotPage extends \Filament\Pages\Page
    {
        use \Livewire\WithFileUploads;

        protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-academic-cap';
        protected static ?string $navigationLabel = 'Train Chatbot';
        protected static ?string $slug = 'chatbot-training';
        protected string $view = 'titan-chatbot::filament.pages.train-chatbot-page';

        public ?int $chatbotId = null;
        public string $url = '';
        public string $textInput = '';
        public string $question = '';
        public string $answer = '';
        public mixed $fileUpload = null;

        public function mount(): void
        {
            $this->chatbotId = Chatbot::query()->value('id');
        }

        public function trainFromUrl(): void
        {
            $this->validate([
                'chatbotId' => ['required', 'integer'],
                'url' => ['required', 'url'],
            ]);

            $chunks = app(TrainingPipeline::class)->ingest(
                chatbotId: $this->chatbotId,
                sourceType: 'website',
                content: $this->url,
                metadata: [
                    'source_url' => $this->url,
                    'engine' => 'openai',
                ],
            );

            $this->url = '';
            $this->notifyTrainingResult($chunks);
        }

        public function trainFromFile(): void
        {
            $this->validate([
                'chatbotId' => ['required', 'integer'],
                'fileUpload' => ['required', 'file', 'mimes:pdf,docx,xlsx,xls,csv,txt,json'],
            ]);

            $storedPath = $this->fileUpload->store('titan-echo-assist-training');
            $absolutePath = storage_path('app/' . $storedPath);

            $chunks = app(TrainingPipeline::class)->ingest(
                chatbotId: $this->chatbotId,
                sourceType: 'file',
                content: $absolutePath,
                metadata: [
                    'file_path' => $absolutePath,
                    'file_name' => $this->fileUpload->getClientOriginalName(),
                    'file' => $storedPath,
                    'title' => $this->fileUpload->getClientOriginalName(),
                    'engine' => 'openai',
                ],
            );

            $this->fileUpload = null;
            $this->notifyTrainingResult($chunks);
        }

        public function trainFromText(): void
        {
            $this->validate([
                'chatbotId' => ['required', 'integer'],
                'textInput' => ['required', 'string'],
            ]);

            $chunks = app(TrainingPipeline::class)->ingest(
                chatbotId: $this->chatbotId,
                sourceType: 'text',
                content: $this->textInput,
                metadata: ['engine' => 'openai'],
            );

            $this->textInput = '';
            $this->notifyTrainingResult($chunks);
        }

        public function trainFromQa(): void
        {
            $this->validate([
                'chatbotId' => ['required', 'integer'],
                'question' => ['required', 'string'],
                'answer' => ['required', 'string'],
            ]);

            $chunks = app(TrainingPipeline::class)->ingest(
                chatbotId: $this->chatbotId,
                sourceType: 'qa',
                content: "Q: {$this->question}\nA: {$this->answer}",
                metadata: [
                    'title' => $this->question,
                    'engine' => 'openai',
                ],
            );

            $this->question = '';
            $this->answer = '';
            $this->notifyTrainingResult($chunks);
        }

        /**
         * @return array<int, string>
         */
        public function chatbotOptions(): array
        {
            return Chatbot::query()
                ->orderBy('id')
                ->pluck('title', 'id')
                ->map(fn (mixed $title, mixed $id): string => trim((string) $title) !== '' ? (string) $title : 'Chatbot #' . $id)
                ->toArray();
        }

        private function notifyTrainingResult(int $chunks): void
        {
            \Filament\Notifications\Notification::make()
                ->title('Training complete')
                ->body("Created {$chunks} embedded chunk(s).")
                ->success()
                ->send();
        }
    }
} else {
    class TrainChatbotPage
    {
        /**
         * @return array<int, string>
         */
        public function chatbotOptions(): array
        {
            return [];
        }
    }
}
