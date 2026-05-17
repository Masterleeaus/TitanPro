<?php

namespace Modules\TitanEchoAssist\Filament\Resources;

use Modules\TitanEchoAssist\Models\Chatbot;

if (class_exists(\Filament\Resources\Resource::class)) {
    class ChatbotResource extends \Filament\Resources\Resource
    {
        protected static ?string $model = Chatbot::class;

        protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-chat-bubble-left-right';

        protected static ?string $navigationLabel = 'Chatbots';

        public static function getModelLabel(): string
        {
            return 'Chatbot';
        }

        public static function form(\Filament\Forms\Form $form): \Filament\Forms\Form
        {
            return $form->schema([
                \Filament\Forms\Components\Tabs::make('Chatbot')
                    ->tabs([
                        \Filament\Forms\Components\Tabs\Tab::make('General')
                            ->schema([
                                \Filament\Forms\Components\TextInput::make('title')
                                    ->required()
                                    ->maxLength(255),
                                \Filament\Forms\Components\TextInput::make('ai_model')
                                    ->required()
                                    ->maxLength(255),
                                \Filament\Forms\Components\Textarea::make('bubble_message')
                                    ->rows(2),
                                \Filament\Forms\Components\Toggle::make('active')
                                    ->default(true),
                            ]),
                        \Filament\Forms\Components\Tabs\Tab::make('Appearance')
                            ->schema([
                                \Filament\Forms\Components\Grid::make(2)
                                    ->schema([
                                        \Filament\Forms\Components\Select::make('bubble_design')
                                            ->options([
                                                'blank' => 'Blank',
                                                'plain' => 'Plain',
                                                'links' => 'Links',
                                                'modern' => 'Modern',
                                                'suggestions' => 'Suggestions',
                                                'promo_banner' => 'Promo Banner',
                                            ])
                                            ->default('modern')
                                            ->required(),
                                        \Filament\Forms\Components\Select::make('position')
                                            ->options([
                                                'left' => 'Left',
                                                'right' => 'Right',
                                            ])
                                            ->default('right')
                                            ->required(),
                                        \Filament\Forms\Components\ColorPicker::make('trigger_background'),
                                        \Filament\Forms\Components\ColorPicker::make('trigger_foreground'),
                                        \Filament\Forms\Components\Select::make('header_bg')
                                            ->options([
                                                'color' => 'Color',
                                                'gradient' => 'Gradient',
                                                'image' => 'Image',
                                            ])
                                            ->default('color'),
                                        \Filament\Forms\Components\ColorPicker::make('header_bg_color'),
                                        \Filament\Forms\Components\ColorPicker::make('header_bg_gradient_start'),
                                        \Filament\Forms\Components\ColorPicker::make('header_bg_gradient_end'),
                                        \Filament\Forms\Components\FileUpload::make('header_bg_image')
                                            ->image()
                                            ->disk('public')
                                            ->directory('chatbot-headers')
                                            ->imagePreviewHeight('120')
                                            ->nullable(),
                                    ]),
                                \Filament\Forms\Components\FileUpload::make('avatar')
                                    ->label('Avatar')
                                    ->image()
                                    ->disk('public')
                                    ->directory('chatbot-avatars')
                                    ->imagePreviewHeight('80')
                                    ->afterStateUpdated(function ($state, $record): void {
                                        if (! $record || ! $state || ! class_exists(\Modules\TitanEchoAssist\Models\ChatbotAvatar::class)) {
                                            return;
                                        }

                                        \Modules\TitanEchoAssist\Models\ChatbotAvatar::query()->updateOrCreate(
                                            [
                                                'chatbot_id' => $record->id,
                                                'file_path' => $state,
                                            ],
                                            [
                                                'company_id' => $record->company_id,
                                                'user_id' => $record->user_id,
                                                'avatar' => $state,
                                                'file_name' => basename((string) $state),
                                                'mime_type' => null,
                                                'size' => null,
                                            ],
                                        );
                                    }),
                                \Filament\Forms\Components\Toggle::make('show_logo')->default(true),
                                \Filament\Forms\Components\Toggle::make('show_date_time')->default(true),
                                \Filament\Forms\Components\Toggle::make('show_avg_response_time')->default(true),
                                \Filament\Forms\Components\TextInput::make('social_whatsapp')->url()->nullable(),
                                \Filament\Forms\Components\TextInput::make('social_telegram')->url()->nullable(),
                                \Filament\Forms\Components\TextInput::make('social_facebook')->url()->nullable(),
                                \Filament\Forms\Components\TextInput::make('social_instagram')->url()->nullable(),
                                \Filament\Forms\Components\Textarea::make('footer_links')
                                    ->formatStateUsing(static fn ($state): string => is_array($state) ? json_encode($state, JSON_PRETTY_PRINT) : (string) ($state ?? ''))
                                    ->dehydrateStateUsing(static function ($state): ?array {
                                        if (! is_string($state) || trim($state) === '') {
                                            return null;
                                        }

                                        $decoded = json_decode($state, true);
                                        return is_array($decoded) ? $decoded : null;
                                    })
                                    ->helperText('Enter a JSON array/object of footer links')
                                    ->rows(5),
                                \Filament\Forms\Components\TextInput::make('privacy_policy_url')->url()->nullable(),
                                \Filament\Forms\Components\TextInput::make('terms_url')->url()->nullable(),
                                \Filament\Forms\Components\FileUpload::make('promo_banner_image')
                                    ->image()
                                    ->disk('public')
                                    ->directory('chatbot-promos')
                                    ->imagePreviewHeight('120')
                                    ->nullable(),
                                \Filament\Forms\Components\TextInput::make('promo_banner_title')->nullable(),
                                \Filament\Forms\Components\Textarea::make('promo_banner_description')->rows(2)->nullable(),
                                \Filament\Forms\Components\TextInput::make('promo_banner_cta_label')->nullable(),
                                \Filament\Forms\Components\TextInput::make('promo_banner_cta_url')->url()->nullable(),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
        }

        public static function table(\Filament\Tables\Table $table): \Filament\Tables\Table
        {
            return $table
                ->columns([
                    \Filament\Tables\Columns\ImageColumn::make('avatar')
                        ->disk('public')
                        ->circular(),
                    \Filament\Tables\Columns\TextColumn::make('title')
                        ->searchable()
                        ->sortable(),
                    \Filament\Tables\Columns\TextColumn::make('bubble_design')
                        ->badge(),
                    \Filament\Tables\Columns\TextColumn::make('position')
                        ->badge(),
                    \Filament\Tables\Columns\IconColumn::make('active')
                        ->boolean(),
                    \Filament\Tables\Columns\TextColumn::make('updated_at')
                        ->dateTime()
                        ->sortable(),
                ])
                ->filters([]);
        }

        public static function getPages(): array
        {
            return [
                'index'  => \Modules\TitanEchoAssist\Filament\Resources\ChatbotResource\Pages\ListChatbots::route('/'),
                'create' => \Modules\TitanEchoAssist\Filament\Resources\ChatbotResource\Pages\CreateChatbot::route('/create'),
                'edit'   => \Modules\TitanEchoAssist\Filament\Resources\ChatbotResource\Pages\EditChatbot::route('/{record}/edit'),
            ];
        }
    }
} else {
    class ChatbotResource
    {
        public static function getModelLabel(): string
        {
            return 'Chatbot';
        }
    }
}
