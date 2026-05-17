<?php

use App\Support\ThemeRuntime;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

uses(TestCase::class);

beforeEach(function (): void {
    $this->themesPath = storage_path('framework/testing/theme-runtime-themes-' . uniqid());

    config(['theme.base_path' => $this->themesPath]);

    File::deleteDirectory($this->themesPath);
    File::makeDirectory($this->themesPath, 0755, true);

    ThemeRuntime::clearActiveTheme();
    Cache::forget(ThemeRuntime::CACHE_KEY);
    Cache::forget('theme-manager.active-preset');

    $activeFile = storage_path('app/theme-manager/active-preset.json');
    if (File::exists($activeFile)) {
        File::delete($activeFile);
    }
});

afterEach(function (): void {
    ThemeRuntime::clearActiveTheme();
    Cache::forget(ThemeRuntime::CACHE_KEY);
    Cache::forget('theme-manager.active-preset');
    File::deleteDirectory($this->themesPath);
});

function createThemeFixture(string $basePath, string $slug): void
{
    $themeDir = $basePath . DIRECTORY_SEPARATOR . $slug;
    File::makeDirectory($themeDir . DIRECTORY_SEPARATOR . 'css', 0755, true, true);
    File::put($themeDir . DIRECTORY_SEPARATOR . 'theme.json', json_encode(['name' => $slug, 'slug' => $slug]));
    File::put($themeDir . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR . 'theme.css', 'body { color: #123; }');
}

it('activates one theme at a time and persists the active theme state', function () {
    createThemeFixture($this->themesPath, 'alpha');
    createThemeFixture($this->themesPath, 'beta');

    expect(ThemeRuntime::setActiveThemeSlug('alpha'))->toBeTrue()
        ->and(ThemeRuntime::activeThemeSlug())->toBe('alpha');

    expect(ThemeRuntime::setActiveThemeSlug('beta'))->toBeTrue()
        ->and(ThemeRuntime::activeThemeSlug())->toBe('beta');

    $state = json_decode((string) File::get(storage_path('app/theme-manager/active-preset.json')), true);

    expect($state)->toBeArray()
        ->and($state['active_theme'] ?? null)->toBe('beta');
});

it('repairs legacy invalid active theme state and falls back safely', function () {
    File::makeDirectory(storage_path('app/theme-manager'), 0755, true, true);
    File::put(storage_path('app/theme-manager/active-preset.json'), json_encode([
        'theme_slug' => 'missing-theme',
        'updated_at' => now()->toIso8601String(),
    ]));

    expect(ThemeRuntime::activeThemeSlug())->toBeNull();

    $state = json_decode((string) File::get(storage_path('app/theme-manager/active-preset.json')), true);

    expect($state)->toBeArray()
        ->and($state)->not->toHaveKey('theme_slug');
});

it('falls back to filament default when active theme css is missing', function () {
    createThemeFixture($this->themesPath, 'alpha');
    ThemeRuntime::setActiveThemeSlug('alpha');

    File::delete($this->themesPath . DIRECTORY_SEPARATOR . 'alpha' . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR . 'theme.css');

    expect(ThemeRuntime::css())->toBe('')
        ->and(ThemeRuntime::activeThemeSlug())->toBeNull();
});

it('supports legacy persisted key names and reports missing marker file in diagnostics', function () {
    createThemeFixture($this->themesPath, 'alpha');

    File::makeDirectory(storage_path('app/theme-manager'), 0755, true, true);
    File::put(storage_path('app/theme-manager/active-preset.json'), json_encode([
        'theme_slug' => 'alpha',
        'updated_at' => now()->toIso8601String(),
    ]));

    expect(ThemeRuntime::activeThemeSlug())->toBe('alpha');

    File::delete(storage_path('app/theme-manager/active-preset.json'));
    Cache::put(ThemeRuntime::CACHE_KEY, 'alpha', now()->addMinutes(10));

    $diagnostics = ThemeRuntime::diagnostics();

    expect($diagnostics['issues'])->toBeArray()
        ->and(collect($diagnostics['issues'])->contains(fn (string $issue): bool => str_contains($issue, 'marker file is missing')))->toBeTrue();
});

it('normalizes older theme manifest formats for compatibility', function () {
    $themeDir = $this->themesPath . DIRECTORY_SEPARATOR . 'legacy-theme';
    File::makeDirectory($themeDir . DIRECTORY_SEPARATOR . 'css', 0755, true, true);
    File::put($themeDir . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR . 'theme.css', 'body { color: #456; }');
    File::put($themeDir . DIRECTORY_SEPARATOR . 'theme.json', json_encode([
        'title' => 'Legacy Theme',
        'theme_format_version' => 0,
        'css' => 'css/theme.css',
    ]));

    $manifest = ThemeRuntime::themeManifest('legacy-theme');

    expect($manifest)->toBeArray()
        ->and($manifest['slug'] ?? null)->toBe('legacy-theme')
        ->and($manifest['name'] ?? null)->toBe('Legacy Theme')
        ->and($manifest['compatible'] ?? false)->toBeTrue()
        ->and($manifest['stylesheet'] ?? null)->toBe('css/theme.css');
});
