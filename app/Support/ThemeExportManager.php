<?php

declare(strict_types=1);

namespace App\Support;

use App\Models\OrganizationBranding;
use App\Models\PlatformSetting;
use App\Models\RoleUIProfile;
use App\Models\TitanUiComponentOverride;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use ZipArchive;

final class ThemeExportManager
{
    public const FORMAT_THEME_ZIP = 'theme_zip';
    public const FORMAT_UI_PACK = 'ui_pack';
    public const FORMAT_BRANDING_KIT = 'branding_kit';
    public const FORMAT_TENANT_PRESET = 'tenant_preset';
    public const FORMAT_CSS = 'css';
    public const FORMAT_STYLE_DICTIONARY = 'style_dictionary';

    /** @return array<string, string> */
    public function formats(): array
    {
        return [
            self::FORMAT_THEME_ZIP => 'Theme ZIP',
            self::FORMAT_UI_PACK => 'UI Pack',
            self::FORMAT_BRANDING_KIT => 'Branding Kit',
            self::FORMAT_TENANT_PRESET => 'Tenant Preset',
            self::FORMAT_CSS => 'CSS File',
            self::FORMAT_STYLE_DICTIONARY => 'Style Dictionary JSON',
        ];
    }

    /** @return array{path:string,fileName:string,contentType:string} */
    public function export(string $name, string $format, ?int $organizationId = null): array
    {
        $organizationId = $this->resolveOrganizationId($organizationId);

        return match ($format) {
            self::FORMAT_THEME_ZIP => $this->exportThemeZip($name),
            self::FORMAT_UI_PACK => $this->exportUiPack($name, $organizationId),
            self::FORMAT_BRANDING_KIT => $this->exportBrandingKit($name, $organizationId),
            self::FORMAT_TENANT_PRESET => $this->exportTenantPreset($name, $organizationId),
            self::FORMAT_CSS => $this->exportCss($name),
            self::FORMAT_STYLE_DICTIONARY => $this->exportStyleDictionary($name),
            default => throw new \InvalidArgumentException('Unsupported export format: '.$format),
        };
    }

    /** @return array{path:string,fileName:string,contentType:string} */
    private function exportThemeZip(string $name): array
    {
        $payload = app(ThemeTokenManager::class)->exportPayload(PlatformSetting::current());
        $tokens = is_array($payload['resolved'] ?? null) ? $payload['resolved'] : [];
        $path = app(ThemePackManager::class)->buildExportZip($name, $tokens);

        return [
            'path' => $path,
            'fileName' => Str::slug($name).'-theme.zip',
            'contentType' => 'application/zip',
        ];
    }

    /** @return array{path:string,fileName:string,contentType:string} */
    private function exportUiPack(string $name, ?int $organizationId): array
    {
        $zip = $this->createZipPath($name, 'ui-pack');
        $payload = app(ThemeTokenManager::class)->exportPayload(PlatformSetting::current());
        $branding = $this->branding($organizationId);

        $zip['archive']->addFromString('theme.json', json_encode($payload['resolved'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        $zip['archive']->addFromString('component-overrides.json', json_encode($this->componentOverrides($organizationId), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        $zip['archive']->addFromString('dashboard-layout.json', json_encode($this->dashboardLayout($branding), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        $zip['archive']->addFromString('meta.json', json_encode([
            'name' => $name,
            'format' => self::FORMAT_UI_PACK,
            'organization_id' => $organizationId,
            'exported_at' => now()->toIso8601String(),
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        $this->addPreviewImage($zip['archive']);
        $zip['archive']->close();

        return [
            'path' => $zip['path'],
            'fileName' => Str::slug($name).'-ui-pack.zip',
            'contentType' => 'application/zip',
        ];
    }

    /** @return array{path:string,fileName:string,contentType:string} */
    private function exportBrandingKit(string $name, ?int $organizationId): array
    {
        $zip = $this->createZipPath($name, 'branding-kit');
        $branding = $this->branding($organizationId);
        $settings = PlatformSetting::current();
        $semantic = app(ThemeTokenManager::class)->semanticEditorState($settings);

        $zip['archive']->addFromString('branding.json', json_encode([
            'panel_name' => $branding?->panel_name ?: $settings->brandName(),
            'background_type' => $branding?->background_type ?: 'none',
            'background_value' => $branding?->background_value,
            'exported_at' => now()->toIso8601String(),
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        $zip['archive']->addFromString('brand-colors.json', json_encode([
            'primary_color' => $semantic['primary_color'],
            'secondary_color' => $semantic['secondary_color'],
            'accent_color' => $semantic['accent_color'],
            'surface_color' => $semantic['surface_color'],
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        $zip['archive']->addFromString('fonts.json', json_encode([
            'font_heading' => $semantic['font_heading'],
            'font_body' => $semantic['font_body'],
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        $this->addBrandingAsset($zip['archive'], $branding?->logo_path, 'logo');
        $this->addBrandingAsset($zip['archive'], $branding?->favicon_path, 'favicon');
        $zip['archive']->close();

        return [
            'path' => $zip['path'],
            'fileName' => Str::slug($name).'-branding-kit.zip',
            'contentType' => 'application/zip',
        ];
    }

    /** @return array{path:string,fileName:string,contentType:string} */
    private function exportTenantPreset(string $name, ?int $organizationId): array
    {
        $zip = $this->createZipPath($name, 'tenant-preset');
        $branding = $this->branding($organizationId);

        $zip['archive']->addFromString('tenant-preset.json', json_encode([
            'name' => $name,
            'organization_id' => $organizationId,
            'theme' => app(ThemeTokenManager::class)->exportPayload(PlatformSetting::current())['resolved'] ?? [],
            'roles' => $this->roleProfiles($organizationId),
            'menus' => is_array($branding?->menu_items) ? $branding->menu_items : [],
            'dashboard_layouts' => $this->dashboardLayout($branding),
            'component_overrides' => $this->componentOverrides($organizationId),
            'exported_at' => now()->toIso8601String(),
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        $zip['archive']->close();

        return [
            'path' => $zip['path'],
            'fileName' => Str::slug($name).'-tenant-preset.zip',
            'contentType' => 'application/zip',
        ];
    }

    /** @return array{path:string,fileName:string,contentType:string} */
    private function exportCss(string $name): array
    {
        $path = $this->tmpPath($name, 'theme', 'css');
        file_put_contents($path, app(ThemeTokenManager::class)->css(PlatformSetting::current()).PHP_EOL);

        return [
            'path' => $path,
            'fileName' => Str::slug($name).'-theme.css',
            'contentType' => 'text/css',
        ];
    }

    /** @return array{path:string,fileName:string,contentType:string} */
    private function exportStyleDictionary(string $name): array
    {
        $path = $this->tmpPath($name, 'style-dictionary', 'json');
        file_put_contents($path, json_encode(
            app(ThemeTokenManager::class)->styleDictionary(PlatformSetting::current()),
            JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
        ).PHP_EOL);

        return [
            'path' => $path,
            'fileName' => Str::slug($name).'-style-dictionary.json',
            'contentType' => 'application/json',
        ];
    }

    /** @return array<int, array<string, mixed>> */
    private function componentOverrides(?int $organizationId): array
    {
        if (! Schema::hasTable('titan_ui_component_overrides')) {
            return [];
        }

        return TitanUiComponentOverride::query()
            ->whereNull('preset_name')
            ->when(
                $organizationId,
                fn ($query) => $query->whereIn('organization_id', [null, $organizationId]),
                fn ($query) => $query->whereNull('organization_id')
            )
            ->orderBy('component')
            ->orderBy('token_key')
            ->get(['component', 'panel', 'organization_id', 'token_key', 'value'])
            ->toArray();
    }

    /** @return array<int, array<string, mixed>> */
    private function roleProfiles(?int $organizationId): array
    {
        if (! $organizationId || ! Schema::hasTable('role_ui_profiles')) {
            return [];
        }

        return RoleUIProfile::withoutGlobalScopes()
            ->where('organization_id', $organizationId)
            ->orderBy('role')
            ->get([
                'role',
                'primary_color',
                'secondary_color',
                'accent_color',
                'surface_color',
                'hidden_nav_items',
                'widget_layout',
            ])->toArray();
    }

    /** @return array<int, array<string, mixed>> */
    private function dashboardLayout(?OrganizationBranding $branding): array
    {
        if (is_array($branding?->dashboard_layout) && $branding->dashboard_layout !== []) {
            return $branding->dashboard_layout;
        }

        if (! Schema::hasTable('layouts')) {
            return [];
        }

        $row = DB::table('layouts')->where('layout_slug', 'ui-studio-layout')->first();
        if (! $row) {
            return [];
        }

        $decoded = json_decode($row->widgets ?? '[]', true);

        return is_array($decoded) ? $decoded : [];
    }

    private function branding(?int $organizationId): ?OrganizationBranding
    {
        if (! $organizationId || ! Schema::hasTable('organization_brandings')) {
            return null;
        }

        return OrganizationBranding::withoutGlobalScopes()
            ->where('organization_id', $organizationId)
            ->first();
    }

    private function resolveOrganizationId(?int $organizationId): ?int
    {
        if ($organizationId) {
            return $organizationId;
        }

        if (! Schema::hasTable('organization_brandings')) {
            return null;
        }

        return OrganizationBranding::withoutGlobalScopes()->value('organization_id');
    }

    private function addPreviewImage(ZipArchive $zip): void
    {
        $zip->addFromString('preview.png', base64_decode(
            'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChwGA60e6kgAAAABJRU5ErkJggg=='
        ));
    }

    private function addBrandingAsset(ZipArchive $zip, ?string $path, string $prefix): void
    {
        if ($path === null || trim($path) === '') {
            return;
        }

        $disk = Storage::disk('public');
        if (! $disk->exists($path)) {
            return;
        }

        if (str_contains($path, '..')) {
            throw new \RuntimeException('Invalid branding asset path: '.$path);
        }

        $absolutePath = $disk->path($path);
        $realPath = realpath($absolutePath);
        $realRoot = realpath($disk->path(''));
        if (! is_string($realPath) || ! is_string($realRoot) || ! str_starts_with($realPath, rtrim($realRoot, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR)) {
            throw new \RuntimeException('Invalid branding asset location: '.$path);
        }

        $extension = pathinfo($path, PATHINFO_EXTENSION);
        $filename = $prefix.($extension !== '' ? '.'.$extension : '');
        if (! $zip->addFile($realPath, $filename)) {
            throw new \RuntimeException('Unable to add branding asset ('.$prefix.') from '.$realPath.' to export archive.');
        }
    }

    /** @return array{path:string,archive:ZipArchive} */
    private function createZipPath(string $name, string $suffix): array
    {
        if (! class_exists(ZipArchive::class)) {
            throw new \RuntimeException('ZipArchive PHP extension is required for theme export.');
        }

        $path = $this->tmpPath($name, $suffix, 'zip');
        $archive = new ZipArchive();
        if ($archive->open($path, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            throw new \RuntimeException('Failed to create ZIP archive at: '.$path);
        }

        return ['path' => $path, 'archive' => $archive];
    }

    private function tmpPath(string $name, string $suffix, string $extension): string
    {
        return sys_get_temp_dir().'/titan-'.Str::slug($name).'-'.$suffix.'-'.Str::ulid().'.'.$extension;
    }
}
