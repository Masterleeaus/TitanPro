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
 * Widget Editor, Theme Engine, and Menu System into one three-panel interface.
 *
 * Left panel  : component tree / layer list (available widget types + current layout)
 * Centre panel: live admin preview canvas (sortable widget cards)
 * Right panel : context-sensitive property editor (theme, spacing, menus)
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

    /** @var array<int, array{id: string, type: string, label: string, columns: int, order: int, properties?: array<string, mixed>}> */
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

    /** Active live-preview mode key. */
    public string $previewMode = 'desktop';

    /** Breakpoint currently being edited in the layout panel. */
    public string $responsiveBreakpoint = 'desktop';

    /**
     * Responsive token overrides keyed by breakpoint.
     *
     * @var array<string, array<string, int|float>>
     */
    public array $responsiveTokenOverrides = [];

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
        $this->activeTab       = 'branding';
        $this->responsiveTokenOverrides = $this->normalizeResponsiveTokenOverrides(
            is_array($settings->theme_snapshots['ui_studio_responsive_overrides'] ?? null)
                ? $settings->theme_snapshots['ui_studio_responsive_overrides']
                : []
        );
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

    public function selectPreviewMode(string $mode): void
    {
        if (! array_key_exists($mode, $this->previewModes())) {
            return;
        }

        $this->previewMode = $mode;
    }

    public function selectResponsiveBreakpoint(string $breakpoint): void
    {
        if (! array_key_exists($breakpoint, $this->previewModes())) {
            return;
        }

        $this->responsiveBreakpoint = $breakpoint;
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
            'properties' => $type === 'table-card'
                ? ['hidden_columns' => $this->defaultTableHiddenColumns()]
                : [],
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

    public function updateTableColumnVisibility(string $widgetId, string $breakpoint, string $column, bool $hidden): void
    {
        if (! in_array($breakpoint, ['mobile', 'tablet'], true)) {
            return;
        }

        if (! array_key_exists($column, $this->tableColumnOptions())) {
            return;
        }

        foreach ($this->canvasWidgets as &$widget) {
            if ($widget['id'] !== $widgetId || ($widget['type'] ?? null) !== 'table-card') {
                continue;
            }

            $hiddenColumns = is_array($widget['properties']['hidden_columns'] ?? null)
                ? $widget['properties']['hidden_columns']
                : $this->defaultTableHiddenColumns();

            $existingColumns = (array) ($hiddenColumns[$breakpoint] ?? []);
            $validColumns = array_filter($existingColumns, 'is_string');
            $uniqueColumns = array_values(array_unique($validColumns));

            if ($hidden) {
                $uniqueColumns[] = $column;
            } else {
                $uniqueColumns = array_values(array_filter($uniqueColumns, fn (string $item): bool => $item !== $column));
            }

            $hiddenColumns[$breakpoint] = array_values(array_unique($uniqueColumns));
            $widget['properties']['hidden_columns'] = $hiddenColumns;
            break;
        }
        unset($widget);
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
            'theme_snapshots' => array_merge(
                is_array($settings->theme_snapshots) ? $settings->theme_snapshots : [],
                ['ui_studio_responsive_overrides' => $this->normalizeResponsiveTokenOverrides($this->responsiveTokenOverrides)]
            ),
        ]);
        cache()->forget('platform_settings');

        // Persist dashboard layout fallback row for legacy dashboard consumers.
        if (Schema::hasTable('layouts')) {
            $slug = 'ui-studio-layout';
            $userId = (int) (auth()->id() ?? DB::table('users')->min('id') ?? 1);
            $widgets = array_map(
                fn ($w) => [
                    'type' => $w['type'],
                    'data' => [
                        'title' => $w['label'],
                        'columns' => (int) ($w['columns'] ?? 12),
                        'properties' => is_array($w['properties'] ?? null) ? $w['properties'] : [],
                    ],
                ],
                $this->canvasWidgets
            );

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
                'columns' => max(1, min(12, (int) ($w['data']['columns'] ?? 12))),
                'order'   => $i,
                'properties' => is_array($w['data']['properties'] ?? null) ? $w['data']['properties'] : [],
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

    /** @return array<string, array{label: string, viewport: int}> */
    public function previewModes(): array
    {
        return [
            'desktop' => ['label' => 'Desktop', 'viewport' => 1440],
            'tablet' => ['label' => 'Tablet', 'viewport' => 1024],
            'mobile' => ['label' => 'Mobile', 'viewport' => 390],
            'collapsed' => ['label' => 'Collapsed sidebar', 'viewport' => 1440],
            'customer' => ['label' => 'Customer portal', 'viewport' => 390],
        ];
    }

    public function previewViewportWidth(): int
    {
        return (int) ($this->previewModes()[$this->previewMode]['viewport'] ?? 1440);
    }

    /**
     * Resolve active responsive tokens for preview.
     * Fallback order: selected preview mode -> currently edited breakpoint -> desktop defaults.
     *
     * @return array<string, int|float>
     */
    public function activeResponsiveOverrides(): array
    {
        return $this->responsiveTokenOverrides[$this->previewMode]
            ?? $this->responsiveTokenOverrides[$this->responsiveBreakpoint]
            ?? $this->defaultResponsiveTokenOverrides()['desktop'];
    }

    public function previewFrameUrl(): string
    {
        $path = $this->previewMode === 'customer'
            ? (string) config('titan_panels.panels.zerofuss.path', 'zerofuss')
            : (string) config('titan_panels.panels.titanpro.path', 'titanpro');

        return url('/' . ltrim($path, '/'));
    }

    /** @return array<string, string> */
    public function tableColumnOptions(): array
    {
        return [
            'name' => 'Name',
            'status' => 'Status',
            'owner' => 'Owner',
            'updated_at' => 'Updated',
            'actions' => 'Actions',
        ];
    }

    /** @return array<string, array<int, string>> */
    public function defaultTableHiddenColumns(): array
    {
        return [
            'mobile' => ['owner', 'updated_at'],
            'tablet' => ['updated_at'],
        ];
    }

    /**
     * @param  array<string, mixed>|null  $properties
     * @return array<string, array<int, string>>
     */
    public function resolvePreviewTableHiddenColumns(?array $properties = null): array
    {
        $defaults = $this->defaultTableHiddenColumns();
        $current = is_array($properties['hidden_columns'] ?? null) ? $properties['hidden_columns'] : [];

        return [
            'mobile' => array_values(array_unique(array_filter((array) ($current['mobile'] ?? $defaults['mobile']), 'is_string'))),
            'tablet' => array_values(array_unique(array_filter((array) ($current['tablet'] ?? $defaults['tablet']), 'is_string'))),
        ];
    }

    /** @param array<int, string> $mobileHiddenColumns */
    public function shouldShowPreviewTableColumn(string $column, array $mobileHiddenColumns): bool
    {
        $isMobilePreview = in_array($this->previewMode, ['mobile', 'customer'], true);

        return ! ($isMobilePreview && in_array($column, $mobileHiddenColumns, true));
    }

    /** @param array<int, string> $tabletHiddenColumns */
    public function previewTableColumnClass(string $column, array $tabletHiddenColumns): string
    {
        return in_array($column, $tabletHiddenColumns, true) ? 'hidden md:table-cell' : '';
    }

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, array<string, int|float>>
     */
    private function normalizeResponsiveTokenOverrides(array $overrides): array
    {
        $defaults = $this->defaultResponsiveTokenOverrides();
        $normalized = [];

        foreach ($defaults as $breakpoint => $values) {
            $source = is_array($overrides[$breakpoint] ?? null) ? $overrides[$breakpoint] : [];
            $sidebarMin = $breakpoint === 'customer' ? 0 : 56;
            $normalized[$breakpoint] = [
                'sidebar_width' => max($sidebarMin, min(420, (int) ($source['sidebar_width'] ?? $values['sidebar_width']))),
                'heading_scale' => max(0.7, min(1.4, (float) ($source['heading_scale'] ?? $values['heading_scale']))),
                'card_padding' => max(8, min(48, (int) ($source['card_padding'] ?? $values['card_padding']))),
            ];
        }

        return $normalized;
    }

    /** @return array<string, array<string, int|float>> */
    private function defaultResponsiveTokenOverrides(): array
    {
        return [
            'desktop' => ['sidebar_width' => 280, 'heading_scale' => 1.0, 'card_padding' => 20],
            'tablet' => ['sidebar_width' => 240, 'heading_scale' => 0.95, 'card_padding' => 16],
            'mobile' => ['sidebar_width' => 64, 'heading_scale' => 0.85, 'card_padding' => 12],
            'collapsed' => ['sidebar_width' => 64, 'heading_scale' => 1.0, 'card_padding' => 16],
            'customer' => ['sidebar_width' => 0, 'heading_scale' => 0.9, 'card_padding' => 12],
        ];
    }
}
