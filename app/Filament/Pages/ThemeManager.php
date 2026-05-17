<?php

namespace App\Filament\Pages;

use App\Support\ThemePalette;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;

class ThemeManager extends Page
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-swatch';
    protected static ?string $navigationLabel = 'Theme Manager';
    protected static ?string $title = 'Theme Manager';
    protected static string | \UnitEnum | null $navigationGroup = 'Settings';
    protected string $view = 'filament.pages.theme-manager';
    public ?string $activePreset = null;
    public array $activeTheme = [];

    public function mount(): void
    {
        $this->activePreset = $this->storedPreset() ?? ThemePalette::defaultPresetSlug();
        $this->activeTheme = ThemePalette::preset($this->activePreset) ?? [];
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('applyPreset')->label('Apply preset')->form([
                Select::make('preset')->label('Preset')->options(ThemePalette::presetOptions())->default($this->activePreset)->required(),
            ])->action(fn (array $data) => $this->applyPreset((string) $data['preset'])),
            Action::make('resetTheme')->label('Reset')->color('gray')->requiresConfirmation()->action(fn () => $this->clearPreset()),
        ];
    }

    public function applyPreset(string $preset): void
    {
        $theme = ThemePalette::preset($preset);
        if (! $theme) {
            Notification::make()->title('Unknown preset')->danger()->send();
            return;
        }
        $this->activePreset = $preset;
        $this->activeTheme = $theme;
        $dir = storage_path('app/theme-manager');
        if (! File::isDirectory($dir)) {
            File::makeDirectory($dir, 0755, true);
        }
        File::put(storage_path('app/theme-manager/active-preset.json'), json_encode([
            'preset' => $preset,
            'theme' => $theme,
            'updated_at' => now()->toIso8601String(),
        ], JSON_PRETTY_PRINT));
        Cache::put('theme-manager.active-preset', $preset, now()->addDay());
        Notification::make()->title('Theme preset applied')->body(($theme['label'] ?? $preset) . ' applied. Refresh once if another page is already open.')->success()->send();
    }

    public function clearPreset(): void
    {
        $this->activePreset = null;
        $this->activeTheme = [];
        Cache::forget('theme-manager.active-preset');
        $file = storage_path('app/theme-manager/active-preset.json');
        if (File::exists($file)) {
            File::delete($file);
        }
        Notification::make()->title('Theme reset')->success()->send();
    }

    protected function storedPreset(): ?string
    {
        $cached = Cache::get('theme-manager.active-preset');
        if (is_string($cached) && ThemePalette::preset($cached)) {
            return $cached;
        }
        $file = storage_path('app/theme-manager/active-preset.json');
        if (! File::exists($file)) {
            return null;
        }
        $json = json_decode((string) File::get($file), true);
        $preset = is_array($json) ? ($json['preset'] ?? null) : null;
        return is_string($preset) && ThemePalette::preset($preset) ? $preset : null;
    }

    public function getPresetsProperty(): array
    {
        return ThemePalette::presets();
    }
}
