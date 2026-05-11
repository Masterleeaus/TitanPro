<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Models\OrganizationBranding;
use App\Models\PlatformSetting;
use App\Support\OrganizationBrandingResolver;
use App\Models\TitanUiComponentOverride;
use App\Platform\Ui\ComponentRegistry;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

/**
 * UI Studio — unified visual design surface merging the Dashboard Builder,
 * Widget Editor, Theme Engine, and Menu System into a split-screen interface.
 *
 * Left side : component tree / controls / property editor
 * Right side: live sandboxed panel preview
 */
class UiStudio extends Page
{
    use WithFileUploads;

    private const HEX_COLOR_REGEX = '/^#[0-9a-fA-F]{3}(?:[0-9a-fA-F]{3})?$/';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-swatch';

    protected static string|\UnitEnum|null $navigationGroup = 'Platform';

    protected static ?int $navigationSort = 50;

    protected static ?string $navigationLabel = 'UI Studio';

    protected static ?string $title = 'UI Studio';

    protected string $view = 'filament.pages.ui-studio';

    // ── Theme state ──────────────────────────────────────────────────────────

    public string $primaryColor   = '#2563eb';
    public string $secondaryColor = '#0f172a';
    public string $accentColor    = '#14b8a6';
    public string $surfaceColor   = '#f8fafc';
    public string $fontHeading    = 'Figtree';
    public string $fontBody       = 'Figtree';
    public string $fontFamily     = 'Figtree';
    public ?string $logoPath      = null;
    public ?string $faviconPath   = null;
    public TemporaryUploadedFile|null $logoUpload = null;
    public TemporaryUploadedFile|null $faviconUpload = null;
    public string $panelName      = 'TITAN ZERO';
    public string $backgroundType = 'none';
    public ?string $backgroundValue = null;
    public string $customCss      = '';

    // ── Dashboard / layout state ──────────────────────────────────────────────

    /** @var array<int, array{id: string, type: string, label: string, columns: int, order: int}> */
    public array $canvasWidgets = [];

    /** Currently selected widget id on the canvas (for right-panel property edit) */
    public ?string $selectedWidgetId = null;

    // ── Menu state ────────────────────────────────────────────────────────────

    /** @var array<int, array{id: string, label: string, url: string, icon: string, order: int}> */
    public array $menuItems = [];

    // ── Right-panel tab ───────────────────────────────────────────────────────

    public string $activeTab = 'branding'; // branding | layout | menu | components

    // ── Available widget catalogue ────────────────────────────────────────────

    /** @var array<string, string> type => label */
    public array $widgetCatalogue = [];

    // ── Component registry state ──────────────────────────────────────────────

    /** Key of the component currently open in the Visual Inspector. */
    public string $activeComponentKey = '';

    /** Panel id whose overrides are being edited. */
    public string $componentPanel = 'admin';

    /**
     * Live token values for the selected component (token_key => value).
     *
     * @var array<string, string>
     */
    public array $componentTokenValues = [];

    /** Name typed into the "Save as preset" input. */
    public string $newPresetName = '';

    /** Preset selected in the "Apply preset" dropdown. */
    public string $selectedPreset = '';

    /**
     * Available presets for the selected component (refreshed when component changes).
     *
     * @var array<string>
     */
    public array $availablePresets = [];

    /** Active panel id rendered in the live preview iframe. */
    public string $previewPanel = 'titanstudio';

    /** Desktop | tablet | mobile frame width preset. */
    public string $previewFrameSize = 'desktop';

    /** Keep controls scroll aligned with preview scroll. */
    public bool $syncPreviewScroll = false;

    /** @var array<string, string|null> */
    private array $savedThemeSnapshot = [];

    // ─────────────────────────────────────────────────────────────────────────

    public function mount(): void
    {
        $settings = PlatformSetting::current();
        $branding = app(OrganizationBrandingResolver::class)->current();

        $this->primaryColor   = $branding['primary_color'] ?? '#2563eb';
        $this->secondaryColor = $branding['secondary_color'] ?? '#0f172a';
        $this->accentColor    = $settings->accent_color ?? '#14b8a6';
        $this->surfaceColor   = $settings->surface_color ?? '#f8fafc';
        $this->fontHeading    = $branding['font_family'] ?? ($settings->font_heading ?? 'Figtree');
        $this->fontBody       = $branding['font_family'] ?? ($settings->font_body ?? 'Figtree');
        $this->fontFamily     = $branding['font_family'] ?? ($settings->font_body ?? 'Figtree');
        $this->panelName      = $branding['panel_name'] ?? $settings->brandName();
        $this->backgroundType = $branding['background_type'] ?? 'none';
        $this->backgroundValue = $branding['background_value'] ?? null;
        $this->logoPath       = $this->storagePathFromUrl($branding['logo_url'] ?? null);
        $this->faviconPath    = $this->storagePathFromUrl($branding['favicon_url'] ?? null);
        $this->customCss      = $settings->custom_css ?? '';

        $this->widgetCatalogue = $this->buildWidgetCatalogue();
        $this->canvasWidgets   = $this->loadCanvasWidgets();
        $this->menuItems       = $this->loadMenuItems();
        $currentPanel          = $this->resolveCurrentPanelId();
        $this->previewPanel    = $currentPanel;
        $this->componentPanel  = $currentPanel;
        $this->savedThemeSnapshot = $this->themeSnapshot();
        $this->activeTab       = 'branding';
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Page header actions
    // ─────────────────────────────────────────────────────────────────────────

    protected function getHeaderActions(): array
    {
        return [
            Action::make('publish')
                ->label('Publish')
                ->icon('heroicon-m-arrow-up-tray')
                ->color('primary')
                ->requiresConfirmation()
                ->modalHeading('Publish layout changes')
                ->modalDescription('This will overwrite the active admin panel configuration with your current studio changes.')
                ->action('publish'),
        ];
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Livewire actions (called from the Blade view)
    // ─────────────────────────────────────────────────────────────────────────

    public function selectTab(string $tab): void
    {
        $this->activeTab = $tab;
    }

    public function selectWidget(?string $id): void
    {
        $this->selectedWidgetId = $id;
        if ($id !== null) {
            $this->activeTab = 'layout';
        }
    }

    public function addWidget(string $type): void
    {
        $label = $this->widgetCatalogue[$type] ?? ucwords(str_replace(['-', '_'], ' ', $type));

        $this->canvasWidgets[] = [
            'id'      => 'w_' . Str::ulid(),
            'type'    => $type,
            'label'   => $label,
            'columns' => 12,
            'order'   => count($this->canvasWidgets),
        ];
    }

    public function removeWidget(string $id): void
    {
        $this->canvasWidgets = array_values(
            array_filter($this->canvasWidgets, fn ($w) => $w['id'] !== $id)
        );

        if ($this->selectedWidgetId === $id) {
            $this->selectedWidgetId = null;
        }
    }

    public function updateWidgetColumns(string $id, int $columns): void
    {
        $columns = max(1, min(12, $columns));
        foreach ($this->canvasWidgets as &$widget) {
            if ($widget['id'] === $id) {
                $widget['columns'] = $columns;
                break;
            }
        }
        unset($widget);
    }

    /** Receives the reordered widget list from Alpine SortableJS. */
    public function reorderWidgets(array $orderedIds): void
    {
        $indexed = [];
        foreach ($this->canvasWidgets as $w) {
            $indexed[$w['id']] = $w;
        }

        $reordered = [];
        foreach ($orderedIds as $i => $id) {
            if (isset($indexed[$id])) {
                $widget          = $indexed[$id];
                $widget['order'] = $i;
                $reordered[]     = $widget;
            }
        }

        $this->canvasWidgets = $reordered;
    }

    // ── Menu editing ──────────────────────────────────────────────────────────

    public function addMenuItem(): void
    {
        $this->menuItems[] = [
            'id'    => 'm_' . Str::ulid(),
            'label' => 'New item',
            'url'   => '/',
            'icon'  => 'heroicon-o-link',
            'order' => count($this->menuItems),
        ];
    }

    public function removeMenuItem(string $id): void
    {
        $this->menuItems = array_values(
            array_filter($this->menuItems, fn ($m) => $m['id'] !== $id)
        );
    }

    public function updateMenuItem(string $id, string $field, string $value): void
    {
        $allowed = ['label', 'url', 'icon'];
        if (! in_array($field, $allowed, true)) {
            return;
        }

        foreach ($this->menuItems as &$item) {
            if ($item['id'] === $id) {
                $item[$field] = $value;
                break;
            }
        }
        unset($item);
    }

    public function reorderMenuItems(array $orderedIds): void
    {
        $indexed = [];
        foreach ($this->menuItems as $m) {
            $indexed[$m['id']] = $m;
        }

        $reordered = [];
        foreach ($orderedIds as $i => $id) {
            if (isset($indexed[$id])) {
                $item          = $indexed[$id];
                $item['order'] = $i;
                $reordered[]   = $item;
            }
        }

        $this->menuItems = $reordered;
    }

    public function setPreviewFrameSize(string $size): void
    {
        if (! in_array($size, ['desktop', 'tablet', 'mobile'], true)) {
            return;
        }

        $this->previewFrameSize = $size;
    }

    public function previewPanelOptions(): array
    {
        $panels = config('titan_panels.panels', []);
        $user = auth()->user();

        if (! $user) {
            return [];
        }

        return array_filter(
            $panels,
            fn (array $panel): bool => empty($panel['roles']) || $user->hasAnyRole($panel['roles'])
        );
    }

    public function previewPanelUrl(): string
    {
        $panels = $this->previewPanelOptions();
        $fallbackPanelId = $this->resolveCurrentPanelId();
        $panel = $panels[$this->previewPanel] ?? ($panels[$fallbackPanelId] ?? null);

        $path = trim((string) ($panel['path'] ?? ''), '/');

        return $path === '' ? url('/') : url('/' . $path);
    }

    public function updatedPreviewPanel(string $panelId): void
    {
        if (! array_key_exists($panelId, $this->previewPanelOptions())) {
            $this->previewPanel = $this->resolveCurrentPanelId();
        }
    }

    public function previewCssVariables(): array
    {
        return [
            '--color-primary-500' => $this->safeColor($this->primaryColor),
            '--color-secondary-500' => $this->safeColor($this->secondaryColor),
            '--color-accent-500' => $this->safeColor($this->accentColor),
            '--color-surface-50' => $this->safeColor($this->surfaceColor),
            '--font-family' => $this->safeFont($this->fontFamily),
        ];
    }

    public function hasUnsavedThemeChanges(): bool
    {
        return $this->themeSnapshot() !== $this->savedThemeSnapshot;
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Component registry actions
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Livewire lifecycle hook — called whenever $componentPanel is updated via
     * wire:model.  If a component is already open in the inspector, reload its
     * overrides for the new panel.
     */
    public function updatedComponentPanel(): void
    {
        if ($this->activeComponentKey !== '') {
            $this->styleComponent($this->activeComponentKey);
        }
    }

    /**
     * Open a registered component in the Visual Inspector (right panel).
     * Switches to the "components" tab and pre-loads its token values.
     */
    public function styleComponent(string $key): void
    {
        $component = ComponentRegistry::get($key);
        if ($component === null) {
            return;
        }

        $this->activeComponentKey = $key;
        $this->activeTab          = 'components';
        $this->newPresetName      = '';
        $this->selectedPreset     = '';

        // Load saved overrides, falling back to token defaults.
        $saved    = $this->loadOverrides($key);
        $defaults = ComponentRegistry::defaults($key);

        $tokens = [];
        foreach ($component['tokens'] as $token) {
            $tokens[$token['key']] = $saved[$token['key']] ?? $defaults[$token['key']];
        }
        $this->componentTokenValues = $tokens;

        // Refresh available presets.
        $this->availablePresets = $this->fetchPresets($key);
    }

    /**
     * Save the current token values as active overrides for the selected component.
     */
    public function saveComponentOverrides(): void
    {
        if ($this->activeComponentKey === '') {
            return;
        }

        if (! Schema::hasTable('titan_ui_component_overrides')) {
            Notification::make()->title('Table not found')->body('Run migrations first.')->warning()->send();

            return;
        }

        TitanUiComponentOverride::saveTokens(
            $this->activeComponentKey,
            $this->getPanelOrNull(),
            $this->componentTokenValues
        );

        Notification::make()
            ->title('Component overrides saved')
            ->success()
            ->send();
    }

    /**
     * Save the current token values as a named preset.
     */
    public function saveComponentPreset(): void
    {
        $name = trim($this->newPresetName);
        if ($name === '' || $this->activeComponentKey === '') {
            Notification::make()->title('Enter a preset name')->warning()->send();

            return;
        }

        if (! Schema::hasTable('titan_ui_component_overrides')) {
            Notification::make()->title('Table not found')->body('Run migrations first.')->warning()->send();

            return;
        }

        TitanUiComponentOverride::savePreset(
            $this->activeComponentKey,
            $this->getPanelOrNull(),
            $name,
            $this->componentTokenValues
        );

        $this->newPresetName    = '';
        $this->availablePresets = $this->fetchPresets($this->activeComponentKey);

        Notification::make()
            ->title("Preset \"{$name}\" saved")
            ->success()
            ->send();
    }

    /**
     * Apply a named preset to the active overrides and reload the token editor.
     */
    public function applyComponentPreset(): void
    {
        $name = $this->selectedPreset;
        if ($name === '' || $this->activeComponentKey === '') {
            Notification::make()->title('Select a preset to apply')->warning()->send();

            return;
        }

        if (! Schema::hasTable('titan_ui_component_overrides')) {
            Notification::make()->title('Table not found')->body('Run migrations first.')->warning()->send();

            return;
        }

        TitanUiComponentOverride::applyPreset(
            $this->activeComponentKey,
            $this->getPanelOrNull(),
            $name
        );

        // Reload the token editor with the freshly applied values.
        $this->styleComponent($this->activeComponentKey);

        Notification::make()
            ->title("Preset \"{$name}\" applied")
            ->success()
            ->send();
    }

    /**
     * Reset active overrides for the selected component to theme defaults.
     */
    public function resetComponentOverrides(): void
    {
        if ($this->activeComponentKey === '') {
            return;
        }

        if (Schema::hasTable('titan_ui_component_overrides')) {
            TitanUiComponentOverride::resetOverrides(
                $this->activeComponentKey,
                $this->getPanelOrNull()
            );
        }

        // Reset in-memory tokens to registry defaults.
        $this->componentTokenValues = ComponentRegistry::defaults($this->activeComponentKey);

        Notification::make()
            ->title('Component reset to theme defaults')
            ->success()
            ->send();
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Publish
    // ─────────────────────────────────────────────────────────────────────────

    public function publish(): void
    {
        $validated = $this->validate([
            'logoUpload' => 'nullable|image|max:2048',
            'faviconUpload' => 'nullable|image|max:1024',
            'panelName' => 'nullable|string|max:255',
            'primaryColor' => ['required', 'regex:'.self::HEX_COLOR_REGEX],
            'secondaryColor' => ['required', 'regex:'.self::HEX_COLOR_REGEX],
            'fontFamily' => ['nullable', 'regex:/^[\w\s\-]+$/', 'max:120'],
            'backgroundType' => 'required|in:none,gradient,image',
            'backgroundValue' => 'nullable|string|max:500',
        ]);

        if ($validated['backgroundType'] === 'gradient' && $validated['backgroundValue']) {
            $this->validate([
                'backgroundValue' => ['regex:/^linear-gradient\(([#0-9a-fA-F.,%\s-]+)\)$/'],
            ]);
        }

        if ($validated['backgroundType'] === 'image' && $validated['backgroundValue']) {
            $this->validate([
                'backgroundValue' => ['url', 'regex:/^https?:\/\//i'],
            ]);
        }

        $orgId = auth()->user()?->organization_id;
        $settings = PlatformSetting::current();

        if ($orgId) {
            $branding = OrganizationBranding::firstOrCreate(['organization_id' => $orgId]);

            if ($this->logoUpload) {
                if ($branding->logo_path) {
                    Storage::disk('public')->delete($branding->logo_path);
                }
                $branding->logo_path = $this->logoUpload->store($this->brandingDirectory($orgId), 'public');
                $this->logoUpload = null;
            } elseif ($this->logoPath) {
                $branding->logo_path = $this->logoPath;
            }

            if ($this->faviconUpload) {
                if ($branding->favicon_path) {
                    Storage::disk('public')->delete($branding->favicon_path);
                }
                $branding->favicon_path = $this->faviconUpload->store($this->brandingDirectory($orgId), 'public');
                $this->faviconUpload = null;
            } elseif ($this->faviconPath) {
                $branding->favicon_path = $this->faviconPath;
            }

            $branding->fill([
                'panel_name' => $validated['panelName'] ?: null,
                'primary_color' => $validated['primaryColor'],
                'secondary_color' => $validated['secondaryColor'],
                'font_family' => $validated['fontFamily'] ?: null,
                'background_type' => $validated['backgroundType'],
                'background_value' => $validated['backgroundValue'] ?: null,
                'menu_items' => $this->menuItems,
                'dashboard_layout' => $this->canvasWidgets,
            ])->save();
        } else {
            $settings->update([
                'primary_color' => $validated['primaryColor'],
                'secondary_color' => $validated['secondaryColor'],
                'font_heading' => $validated['fontFamily'] ?: 'Figtree',
                'font_body' => $validated['fontFamily'] ?: 'Figtree',
            ]);
        }

        // Persist shared theme settings for app shell preview behavior.
        $settings->update([
            'accent_color' => $this->accentColor,
            'surface_color' => $this->surfaceColor,
            'custom_css' => $this->customCss,
        ]);
        cache()->forget('platform_settings');

        // Persist dashboard layout fallback row for legacy dashboard consumers.
        if (Schema::hasTable('layouts')) {
            $slug = 'ui-studio-layout';
            $userId = (int) (auth()->id() ?? DB::table('users')->min('id') ?? 1);
            $widgets = array_map(fn ($w) => ['type' => $w['type'], 'data' => ['title' => $w['label']]], $this->canvasWidgets);

            DB::table('layouts')->updateOrInsert(
                ['layout_slug' => $slug],
                [
                    'user_id' => $userId,
                    'layout_title' => 'UI Studio Layout',
                    'layout_slug' => $slug,
                    'widgets' => json_encode($widgets),
                    'is_active' => 1,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }

        Notification::make()
            ->title('UI Studio layout published')
            ->body('Branding, dashboard layout, and menu changes have been saved.')
            ->success()
            ->send();

        $this->savedThemeSnapshot = $this->themeSnapshot();
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Helpers
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Sanitize a CSS hex color value to prevent CSS injection.
     * Returns the color if it matches `#rrggbb` or `#rgb`, otherwise the default.
     */
    public function safeColor(string $color, string $default = '#000000'): string
    {
        return preg_match(self::HEX_COLOR_REGEX, $color) ? $color : $default;
    }

    /**
     * Sanitize a CSS font-family name to prevent CSS injection.
     * Allows letters, digits, spaces, hyphens, and underscores only.
     */
    public function safeFont(string $font, string $default = 'Figtree'): string
    {
        return preg_match('/^[\w\s\-]+$/', $font) ? $font : $default;
    }

    public function safeBackgroundStyle(?string $type, ?string $value): ?string
    {
        if (! $type || ! $value) {
            return null;
        }

        if ($type === 'gradient' && preg_match('/^linear-gradient\(([#0-9a-fA-F.,%\s-]+)\)$/', $value)) {
            return "background: {$value}";
        }

        if ($type === 'image' && preg_match('/^https?:\/\//i', $value) && filter_var($value, FILTER_VALIDATE_URL)) {
            return "background-image:url(\"{$value}\");background-size:cover;background-position:center;";
        }

        return null;
    }

    /** @return array<string, string> */
    private function buildWidgetCatalogue(): array
    {
        return [
            'kpi-grid-card'       => 'KPI Grid',
            'alert-notice-card'   => 'Alert / Notice',
            'recent-activity-card'=> 'Recent Activity',
            'cta-button-card'     => 'CTA Button',
            'html-card'           => 'HTML / Rich Text',
            'chart-bar-card'      => 'Bar Chart',
            'chart-line-card'     => 'Line Chart',
            'stat-card'           => 'Stat Card',
            'table-card'          => 'Data Table',
            'map-card'            => 'Live Map',
        ];
    }

    /** @return array<int, array<string, mixed>> */
    private function loadCanvasWidgets(): array
    {
        $orgId = auth()->user()?->organization_id;
        if ($orgId) {
            $branding = OrganizationBranding::query()
                ->where('organization_id', $orgId)
                ->first();

            if (is_array($branding?->dashboard_layout) && count($branding->dashboard_layout) > 0) {
                return $branding->dashboard_layout;
            }
        }

        if (! Schema::hasTable('layouts')) {
            return [];
        }

        $row = DB::table('layouts')->where('layout_slug', 'ui-studio-layout')->first();

        if (! $row) {
            // Fall back to the default "welcome" layout so the canvas is not empty.
            $row = DB::table('layouts')->where('layout_slug', 'welcome-dashboard')->first();
        }

        if (! $row) {
            return [];
        }

        $widgets = json_decode($row->widgets ?? '[]', true) ?: [];

        return array_values(
            array_map(fn (array $w, int $i) => [
                'id'      => 'w_' . Str::ulid(),
                'type'    => $w['type'] ?? 'html-card',
                'label'   => $w['data']['title'] ?? ucwords(str_replace(['-', '_'], ' ', $w['type'] ?? 'Widget')),
                'columns' => 12,
                'order'   => $i,
            ], $widgets, array_keys($widgets))
        );
    }

    /** @return array<int, array<string, mixed>> */
    private function loadMenuItems(): array
    {
        $orgId = auth()->user()?->organization_id;
        if ($orgId) {
            $branding = OrganizationBranding::query()
                ->where('organization_id', $orgId)
                ->first();

            if (is_array($branding?->menu_items) && count($branding->menu_items) > 0) {
                return $branding->menu_items;
            }
        }

        // Default nav items that mirror the main admin sidebar sections.
        return [
            ['id' => 'm_0', 'label' => 'Dashboard',    'url' => '/titanpro',                   'icon' => 'heroicon-o-home',           'order' => 0],
            ['id' => 'm_1', 'label' => 'Jobs',          'url' => '/titanpro/jobs',              'icon' => 'heroicon-o-briefcase',      'order' => 1],
            ['id' => 'm_2', 'label' => 'Customers',     'url' => '/titanpro/customers',         'icon' => 'heroicon-o-users',          'order' => 2],
            ['id' => 'm_3', 'label' => 'Invoices',      'url' => '/titanpro/invoices',          'icon' => 'heroicon-o-document-text',  'order' => 3],
            ['id' => 'm_4', 'label' => 'Site Settings', 'url' => '/titanpro/site-settings',     'icon' => 'heroicon-o-paint-brush',    'order' => 4],
        ];
    }

    private function storagePathFromUrl(?string $url): ?string
    {
        if (! $url) {
            return null;
        }

        $marker = '/storage/';
        $position = strpos($url, $marker);

        if ($position === false) {
            return null;
        }

        return substr($url, $position + strlen($marker));
    }

    private function brandingDirectory(int $organizationId): string
    {
        return "organization-branding/{$organizationId}";
    }

    /**
     * Load saved active overrides for a component + panel from the DB.
     * Returns an empty array if the table does not yet exist.
     *
     * @return array<string, string>
     */
    private function loadOverrides(string $componentKey): array
    {
        if (! Schema::hasTable('titan_ui_component_overrides')) {
            return [];
        }

        return TitanUiComponentOverride::loadTokens(
            $componentKey,
            $this->getPanelOrNull()
        );
    }

    /**
     * Fetch the list of named presets available for a component + panel.
     *
     * @return array<string>
     */
    private function fetchPresets(string $componentKey): array
    {
        if (! Schema::hasTable('titan_ui_component_overrides')) {
            return [];
        }

        return TitanUiComponentOverride::presetNames(
            $componentKey,
            $this->getPanelOrNull()
        );
    }

    /**
     * Return the active panel as a non-empty string, or null for platform-wide.
     */
    private function getPanelOrNull(): ?string
    {
        return $this->componentPanel !== '' ? $this->componentPanel : null;
    }

    private function resolveCurrentPanelId(): string
    {
        $currentPath = trim((string) request()->segment(1), '/');
        $panels = config('titan_panels.panels', []);

        foreach ($panels as $id => $panel) {
            if (($panel['path'] ?? null) === $currentPath) {
                return (string) $id;
            }
        }

        return array_key_first($panels) ?? 'titanpro';
    }

    /** @return array<string, string|null> */
    private function themeSnapshot(): array
    {
        return [
            'panelName' => $this->panelName,
            'primaryColor' => $this->primaryColor,
            'secondaryColor' => $this->secondaryColor,
            'accentColor' => $this->accentColor,
            'surfaceColor' => $this->surfaceColor,
            'fontFamily' => $this->fontFamily,
            'backgroundType' => $this->backgroundType,
            'backgroundValue' => $this->backgroundValue,
            'customCss' => $this->customCss,
        ];
    }
}
