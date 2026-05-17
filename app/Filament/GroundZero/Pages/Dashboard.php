<?php

namespace App\Filament\GroundZero\Pages;

use App\Support\GroundZero\GroundZeroCommandBus;
use App\Support\GroundZero\GroundZeroRuntime;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class Dashboard extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-sparkles';
    protected static string|\UnitEnum|null $navigationGroup = 'GroundZero';
    protected static ?string $navigationLabel = 'Workspace';
    protected static ?string $title = 'GroundZero';
    protected static ?int $navigationSort = 1;
    protected static ?string $slug = '/';

    protected string $view = 'filament.groundzero.pages.dashboard';

    public string $command = '';

    /** @var array<int, array<string, mixed>> */
    public array $messages = [];

    /** @var array<int, array<string, string>> */
    public array $timeline = [];

    /** @var array<string, bool> */
    public array $runtimeStatus = [];

    /** @var array<string, mixed> */
    public array $lastResult = [];

    public function mount(): void
    {
        $runtime = app(GroundZeroRuntime::class);

        $this->runtimeStatus = $runtime->status();
        $this->timeline = $runtime->timeline();

        $this->messages = [[
            'role' => 'assistant',
            'title' => 'GroundZero is online',
            'body' => 'Ask me to inspect jobs, customers, schedules, invoices, cleaners, or anything stored in Titan Pro. TitanZero is the chat layer and TitanCore is the orchestration layer when those providers are configured.',
            'cards' => $this->statusCards($this->runtimeStatus),
        ]];
    }

    public function send(): void
    {
        $text = trim($this->command);

        if ($text === '') {
            Notification::make()
                ->title('Type a command first')
                ->warning()
                ->send();

            return;
        }

        $this->messages[] = [
            'role' => 'user',
            'title' => 'You',
            'body' => $text,
            'cards' => [],
        ];

        $result = app(GroundZeroCommandBus::class)->dispatch($text, [
            'panel' => 'groundzero',
            'user_id' => auth()->id(),
        ]);

        $this->lastResult = $result;
        $this->messages[] = [
            'role' => 'assistant',
            'title' => $result['title'] ?? 'GroundZero',
            'body' => $result['message'] ?? 'Command complete.',
            'cards' => $result['cards'] ?? [],
        ];

        $this->timeline = app(GroundZeroRuntime::class)->timeline();
        $this->command = '';
    }

    /**
     * Backward-compatible no-op snapshot for any stale dashboard blade/cache that still calls snapshot().
     * Keeps GroundZero alive while the new dashboard view is deployed and caches are cleared.
     *
     * @return array<string, mixed>
     */
    public function snapshot(): array
    {
        return [
            'runtime' => $this->runtimeStatus,
            'timeline' => $this->timeline,
            'last_result' => $this->lastResult,
        ];
    }

    /**
     * @param array<string, bool> $status
     * @return array<int, array<string, string>>
     */
    private function statusCards(array $status): array
    {
        return collect($status)
            ->map(fn (bool $ready, string $key): array => [
                'label' => str_replace('_', ' ', ucfirst($key)),
                'value' => $ready ? 'Ready' : 'Missing',
                'tone' => $ready ? 'success' : 'warning',
            ])
            ->values()
            ->all();
    }
}
