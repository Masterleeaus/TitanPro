<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Filament\Pages\UiStudio\WidgetPropertyRegistry;
use App\Models\AiThemeSnapshot;
use App\Models\OrganizationBranding;
use App\Models\PlatformSetting;
use App\Models\RoleUIProfile;
use App\Models\SharedTheme;
use App\Models\TitanThemeVersion;
use App\Services\AiThemeGenerator;
use App\Support\OrganizationBrandingResolver;
use App\Support\ThemeTokenManager;
use App\Support\ThemePackManager;
use App\Models\TitanUiComponentOverride;
use App\Platform\Ui\ComponentRegistry;
use Filament\Facades\Filament;
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
    public string $customCss = '';
    public string $previewMode = 'desktop';
    public int $previewViewportWidth = 1440;

    use WithFileUploads;

    private const HEX_COLOR_REGEX = '/^#[0-9a-fA-F]{3}(?:[0-9a-fA-F]{3})?$/';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-swatch';

    protected static string|\UnitEnum|null $navigationGroup = 'Appearance';

    protected static ?int $navigationSort = 50;

    protected static ?string $navigationLabel = 'UI Manager';

    protected static ?string $title = 'UI Manager';

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

    // ── Dashboard / layout state ──────────────────────────────────────────────

    /** @var array<int, array{id: string, type: string, label: string, columns: int, order: int}> */
    public array $canvasWidgets = [];

    /** Currently selected widget id on the canvas (for right-panel property edit) */
    public ?string $selectedWidgetId = null;

    /**
     * Live property values for the selected widget (property_key => value).
     * Mirrors the same editing-state pattern used by componentTokenValues.
     *
     * @var array<string, mixed>
     */
    public array $widgetPropertyValues = [];

    // ── Menu state ────────────────────────────────────────────────────────────

    /** @var array<int, array{id: string, label: string, url: string, icon: string, order: int}> */
    public array $menuItems = [];

    // ── Right-panel tab ───────────────────────────────────────────────────────

    public string $activeTab = 'branding'; // branding | layout | menu | roles | components | marketplace

    // ── Role Profiles state ───────────────────────────────────────────────────

    /** @var array<string, array{primary_color: string, secondary_color: string, accent_color: string, surface_color: string, hidden_nav_items: list<string>, widget_layout: list<string>}> */
    public array $roleProfiles = [];

    /** Currently selected role slug in the Role Profiles tab. */
    public ?string $selectedRole = null;

    // ── Marketplace state ─────────────────────────────────────────────────────

    /** Sub-tab within the Marketplace tab. */
    public string $marketplaceTab = 'browse'; // browse | install | share | import

    /** Uploaded ZIP for the Install flow. */
    public ?TemporaryUploadedFile $themeZipUpload = null;

    /** Preview data parsed from an uploaded ZIP. */
    public array $zipPreview = [];

    /** Name override used when sharing the active theme. */
    public string $shareThemeName = '';

    /** Generated share URL after calling shareTheme(). */
    public string $generatedShareUrl = '';

    /** Share link URL pasted in the Import flow. */
    public string $importUrl = '';

    /** Theme preview resolved from an import URL. */
    public array $importPreview = [];

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

    /** Live Preview device/frame mode. */
    public string $previewFrameSize = 'desktop';

    /** Breakpoint whose responsive token overrides are visible in the editor. */
    public string $activeResponsiveBreakpoint = 'desktop';

    /**
     * Responsive token overrides, stored separately from base theme tokens.
     *
     * @var array<string, array<string, string>>
     */
    public array $responsiveTokens = [];

    /**
     * Per-table mobile visibility controls. Keys map to table/resource identifiers.
     *
     * @var array<string, array<string, bool>>
     */
    public array $responsiveTableColumns = [];

    /** @var array<int, array{version_number:int,label:string,created_at:string,created_by:string}> */
    public array $themeVersions = [];

    public string $versionLabel = '';

    public ?int $diffFromVersion = null;

    public ?int $diffToVersion = null;

    /** @var array<int, array{token:string,left:string,right:string,changed:bool}> */
    public array $versionDiffRows = [];

    /** Keep controls scroll aligned with preview scroll. */
    public bool $syncPreviewScroll = false;

    /** @var array<string, string|null> */
    private array $savedThemeSnapshot = [];

    // ── AI Theme Generator modal state ────────────────────────────────────────

    /** Whether the AI theme modal is visible. */
    public bool $showAiModal = false;

    /**
     * Current step in the AI generation flow.
     * Values: 'prompt' | 'generating' | 'preview'
     */
    public string $aiModalStep = 'prompt';

    /** The user-typed natural-language prompt. */
    public string $aiPrompt = '';

    /**
     * Token values returned by the AI generator.
     *
     * @var array<string, string>
     */
    public array $aiGeneratedTheme = [];

    /** Error message shown when generation fails. */
    public string $aiErrorMessage = '';

    // ─────────────────────────────────────────────────────────────────────────

    public function mount(): void
    {
        $settings = PlatformSetting::current();
        $branding = app(OrganizationBrandingResolver::class)->current();
        $tokenState = app(ThemeTokenManager::class)->semanticEditorState($settings);

        $this->primaryColor   = $branding['primary_color'] ?? $tokenState['primary_color'];
        $this->secondaryColor = $branding['secondary_color'] ?? $tokenState['secondary_color'];
        $this->accentColor    = $tokenState['accent_color'];
        $this->surfaceColor   = $tokenState['surface_color'];
        $this->fontHeading    = $branding['font_family'] ?? $tokenState['font_heading'];
        $this->fontBody       = $branding['font_family'] ?? $tokenState['font_body'];
        $this->fontFamily     = $branding['font_family'] ?? $tokenState['font_body'];
        $this->panelName      = $branding['panel_name'] ?? $settings->brandName();
        $this->backgroundType = $branding['background_type'] ?? 'none';
        $this->backgroundValue = $branding['background_value'] ?? null;
        $this->logoPath       = $this->storagePathFromUrl($branding['logo_url'] ?? null);
        $this->faviconPath    = $this->storagePathFromUrl($branding['favicon_url'] ?? null);

        $this->responsiveTokens = app(ThemeTokenManager::class)->responsiveEditorState($settings);
        $this->responsiveTableColumns = $this->loadResponsiveTableColumns();

        $this->widgetCatalogue = $this->buildWidgetCatalogue();
        $this->canvasWidgets   = $this->loadCanvasWidgets();
        $this->menuItems       = $this->loadMenuItems();
        $this->roleProfiles    = $this->loadRoleProfiles();
        $currentPanel          = $this->resolveCurrentPanelId();
        $this->previewPanel    = $currentPanel;
        $this->componentPanel  = $currentPanel;
        $this->savedThemeSnapshot = $this->themeSnapshot();
        $this->activeTab       = 'branding';
        $this->loadThemeVersions();

        // If redirected from a share link, auto-open the import tab.
        $importToken = request()->query('import_token');
        if ($importToken) {
            $this->activeTab      = 'marketplace';
            $this->marketplaceTab = 'import';
            $this->importUrl      = url('/theme/import/' . $importToken);
        }
    }

    public static function canAccess(): bool
    {
        $user = auth()->user();

        if (! $user) {
            return false;
        }

        $panelId = Filament::getCurrentPanel()?->getId();

        if (! $panelId) {
            return false;
        }

        if ($panelId === 'titanpro') {
            return $user->hasRole('super_admin');
        }

        $panelRoles = config("titan_panels.panels.{$panelId}.roles", []);
        $uiStudioRoles = array_values(array_intersect($panelRoles, ['owner', 'admin']));

        if ($uiStudioRoles === []) {
            return false;
        }

        return $user->hasRole($uiStudioRoles);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Page header actions
    // ─────────────────────────────────────────────────────────────────────────

    protected function getHeaderActions(): array
    {
        return [
            Action::make('aiGenerate')
                ->label('AI Generate')
                ->icon('heroicon-m-sparkles')
                ->color('warning')
                ->action('openAiModal'),
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

    // ─────────────────────────────────────────────────────────────────────────
    // AI Theme Generator
    // ─────────────────────────────────────────────────────────────────────────

    /** Open the AI Theme Generator modal and reset its state. */
    public function openAiModal(): void
    {
        $this->aiPrompt         = '';
        $this->aiGeneratedTheme = [];
        $this->aiErrorMessage   = '';
        $this->aiModalStep      = 'prompt';
        $this->showAiModal      = true;
    }

    /** Close the AI Theme Generator modal without applying any changes. */
    public function closeAiModal(): void
    {
        $this->showAiModal      = false;
        $this->aiModalStep      = 'prompt';
        $this->aiGeneratedTheme = [];
        $this->aiErrorMessage   = '';
    }

    /**
     * Call the Claude AI API with the user's prompt and transition to the preview step.
     *
     * Rate-limited to a maximum of 5 generations per organisation per day.
     */
    public function generateAiTheme(): void
    {
        $prompt = trim($this->aiPrompt);

        if ($prompt === '') {
            $this->aiErrorMessage = 'Please describe the design you want to generate.';

            return;
        }

        $orgId = auth()->user()?->organization_id;

        if ($orgId !== null && Schema::hasTable('ai_theme_snapshots')) {
            $dailyCount = AiThemeSnapshot::todayCountForOrg($orgId);

            if ($dailyCount >= 5) {
                $this->aiErrorMessage = 'Daily limit reached (5 generations per organisation per day). Try again tomorrow.';

                return;
            }
        }

        $this->aiErrorMessage = '';
        $this->aiModalStep    = 'generating';
        $this->createThemeVersion('Auto snapshot before AI generation');

        try {
            $this->aiGeneratedTheme = app(AiThemeGenerator::class)->generate($prompt);
            $this->aiModalStep      = 'preview';
        } catch (\RuntimeException $e) {
            $this->aiErrorMessage = $e->getMessage();
            $this->aiModalStep    = 'prompt';
        }
    }

    /** Return to the prompt step to refine and regenerate. */
    public function regenerateAiTheme(): void
    {
        $this->aiGeneratedTheme = [];
        $this->aiErrorMessage   = '';
        $this->aiModalStep      = 'prompt';
    }

    /**
     * Apply the generated theme to the active UiStudio state and save a snapshot.
     * The snapshot is named "AI: {short prompt} — {date}".
     */
    public function acceptAiTheme(): void
    {
        $theme = $this->aiGeneratedTheme;

        if (empty($theme)) {
            return;
        }

        // Apply core colours to the live editor state.
        $this->primaryColor   = $theme['primary_color']   ?? $this->primaryColor;
        $this->secondaryColor = $theme['secondary_color'] ?? $this->secondaryColor;
        $this->accentColor    = $theme['accent_color']    ?? $this->accentColor;
        $this->surfaceColor   = $theme['surface_color']   ?? $this->surfaceColor;
        $this->fontHeading    = $theme['font_heading']    ?? $this->fontHeading;
        $this->fontBody       = $theme['font_body']       ?? $this->fontBody;
        $this->fontFamily     = $theme['font_heading']    ?? $this->fontFamily;

        // Persist as a named snapshot.
        if (Schema::hasTable('ai_theme_snapshots')) {
            $orgId  = auth()->user()?->organization_id;
            $userId = (int) (auth()->id() ?? 0) ?: null;

            AiThemeSnapshot::createFromGeneration(
                $orgId,
                $userId,
                $this->aiPrompt,
                $theme
            );
        }

        $this->closeAiModal();

        Notification::make()
            ->title('AI theme applied')
            ->body('Review the colours and typography in the Branding panel, then click Publish to save.')
            ->success()
            ->send();
    }

    /** Discard the generated theme and close the modal. */
    public function discardAiTheme(): void
    {
        $this->closeAiModal();
    }

    public function selectWidget(?string $id): void
    {
        $this->selectedWidgetId = $id;
        if ($id !== null) {
            $this->activeTab = 'layout';
            $this->loadWidgetPropertyValues($id);
        } else {
            $this->widgetPropertyValues = [];
        }
    }

    public function addWidget(string $type): void
    {
        $label = $this->widgetCatalogue[$type] ?? ucwords(str_replace(['-', '_'], ' ', $type));

        $this->canvasWidgets[] = [
            'id'         => 'w_' . Str::ulid(),
            'type'       => $type,
            'label'      => $label,
            'columns'    => 12,
            'order'      => count($this->canvasWidgets),
            'properties' => WidgetPropertyRegistry::defaults($type),
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

    /**
     * Update a single property value for the selected widget.
     * The value is written both to the live $widgetPropertyValues editor state
     * and back into the matching entry in $canvasWidgets.
     */
    public function updateWidgetProperty(string $key, mixed $value): void
    {
        if ($this->selectedWidgetId === null) {
            return;
        }

        // Update in-memory editor state.
        $this->widgetPropertyValues[$key] = $value;

        // Write through to the canvas widget so publish() always has fresh data.
        foreach ($this->canvasWidgets as &$widget) {
            if ($widget['id'] === $this->selectedWidgetId) {
                if (! isset($widget['properties']) || ! is_array($widget['properties'])) {
                    $widget['properties'] = [];
                }
                $widget['properties'][$key] = $value;
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

    // ── Role Profiles ─────────────────────────────────────────────────────────

    public function selectRole(?string $role): void
    {
        if ($role !== null && ! array_key_exists($role, RoleUIProfile::SUPPORTED_ROLES)) {
            return;
        }
        $this->selectedRole = $role;
    }

    public function updateRoleProfile(string $role, string $field, string $value): void
    {
        if (! array_key_exists($role, RoleUIProfile::SUPPORTED_ROLES)) {
            return;
        }

        $allowed = ['primary_color', 'secondary_color', 'accent_color', 'surface_color'];
        if (! in_array($field, $allowed, true)) {
            return;
        }

        if (! isset($this->roleProfiles[$role])) {
            $this->roleProfiles[$role] = $this->defaultRoleProfile();
        }

        $this->roleProfiles[$role][$field] = $value;
    }

    public function toggleNavItem(string $role, string $item): void
    {
        if (! array_key_exists($role, RoleUIProfile::SUPPORTED_ROLES)) {
            return;
        }

        if (! isset($this->roleProfiles[$role])) {
            $this->roleProfiles[$role] = $this->defaultRoleProfile();
        }

        $hidden = $this->roleProfiles[$role]['hidden_nav_items'] ?? [];

        if (in_array($item, $hidden, true)) {
            $this->roleProfiles[$role]['hidden_nav_items'] = array_values(
                array_filter($hidden, fn ($i) => $i !== $item)
            );
        } else {
            $hidden[] = $item;
            $this->roleProfiles[$role]['hidden_nav_items'] = $hidden;
        }
    }

    public function updateRoleWidgetLayout(string $role, string $widgetType, bool $enabled): void
    {
        if (! array_key_exists($role, RoleUIProfile::SUPPORTED_ROLES)) {
            return;
        }

        if (! isset($this->roleProfiles[$role])) {
            $this->roleProfiles[$role] = $this->defaultRoleProfile();
        }

        $layout = $this->roleProfiles[$role]['widget_layout'] ?? [];

        if ($enabled && ! in_array($widgetType, $layout, true)) {
            $layout[] = $widgetType;
        } elseif (! $enabled) {
            $layout = array_values(array_filter($layout, fn ($t) => $t !== $widgetType));
        }

        $this->roleProfiles[$role]['widget_layout'] = $layout;
    }

    public function setPreviewFrameSize(string $size): void
    {
        if (! array_key_exists($size, $this->previewModes())) {
            return;
        }

        $this->previewFrameSize = $size;
        $this->activeResponsiveBreakpoint = $this->breakpointForPreviewMode($size);
    }

    public function setActiveResponsiveBreakpoint(string $breakpoint): void
    {
        if (! in_array($breakpoint, ['desktop', 'tablet', 'mobile'], true)) {
            return;
        }

        $this->activeResponsiveBreakpoint = $breakpoint;
    }

    public function updateResponsiveToken(string $breakpoint, string $token, string $value): void
    {
        if (! in_array($breakpoint, ['desktop', 'tablet', 'mobile'], true)) {
            return;
        }

        if (! array_key_exists($token, $this->responsiveTokenDefinitions())) {
            return;
        }

        $this->responsiveTokens[$breakpoint][$token] = $this->sanitizeResponsiveTokenValue($token, $value);
    }

    public function updateResponsiveTableColumn(string $table, string $column, bool $visible): void
    {
        $this->responsiveTableColumns[$table][$column] = $visible;
    }

    public function previewModes(): array
    {
        return [
            'desktop' => ['label' => 'Desktop', 'width' => 1440, 'breakpoint' => 'desktop', 'description' => '1440px admin dashboard'],
            'tablet' => ['label' => 'Tablet', 'width' => 1024, 'breakpoint' => 'tablet', 'description' => '1024px tablet view'],
            'mobile' => ['label' => 'Mobile', 'width' => 390, 'breakpoint' => 'mobile', 'description' => '390px no-scroll mobile view'],
            'collapsed' => ['label' => 'Collapsed sidebar', 'width' => 1440, 'breakpoint' => 'desktop', 'description' => '1440px with icon-only sidebar'],
            'customer' => ['label' => 'Customer portal', 'width' => 390, 'breakpoint' => 'mobile', 'description' => 'Customer-facing portal simulation'],
        ];
    }

    public function previewFrameWidth(): int
    {
        return (int) ($this->previewModes()[$this->previewFrameSize]['width'] ?? 1440);
    }

    public function responsiveTokenDefinitions(): array
    {
        return [
            '--sidebar-width' => ['label' => 'Sidebar width', 'type' => 'length', 'desktop' => '280px', 'tablet' => '220px', 'mobile' => '64px'],
            '--content-gap' => ['label' => 'Layout gap', 'type' => 'length', 'desktop' => '24px', 'tablet' => '20px', 'mobile' => '12px'],
            '--card-padding' => ['label' => 'Card padding', 'type' => 'length', 'desktop' => '24px', 'tablet' => '18px', 'mobile' => '12px'],
            '--heading-xl-size' => ['label' => 'Heading XL', 'type' => 'length', 'desktop' => '32px', 'tablet' => '28px', 'mobile' => '22px'],
            '--heading-lg-size' => ['label' => 'Heading LG', 'type' => 'length', 'desktop' => '24px', 'tablet' => '22px', 'mobile' => '18px'],
            '--body-font-size' => ['label' => 'Body font size', 'type' => 'length', 'desktop' => '16px', 'tablet' => '15px', 'mobile' => '14px'],
            '--table-cell-padding-x' => ['label' => 'Table X padding', 'type' => 'length', 'desktop' => '16px', 'tablet' => '12px', 'mobile' => '8px'],
        ];
    }

    private function breakpointForPreviewMode(string $mode): string
    {
        return (string) ($this->previewModes()[$mode]['breakpoint'] ?? 'desktop');
    }

    private function defaultResponsiveTableColumns(): array
    {
        return [
            'resource_tables' => [
                'id' => false,
                'created_at' => false,
                'updated_at' => false,
                'status' => true,
                'actions' => true,
            ],
        ];
    }

    private function loadResponsiveTableColumns(): array
    {
        $defaults = $this->defaultResponsiveTableColumns();

        if (! Schema::hasTable('titan_theme_tokens')) {
            return $defaults;
        }

        $stored = DB::table('titan_theme_tokens')
            ->where('panel', 'global')
            ->where('scope', 'responsive:tables')
            ->where('key', 'resource_tables')
            ->value('value');

        if (! is_string($stored) || $stored === '') {
            return $defaults;
        }

        $decoded = json_decode($stored, true);

        if (! is_array($decoded)) {
            return $defaults;
        }

        return array_replace_recursive($defaults, $decoded);
    }

    private function saveResponsiveTableColumns(): void
    {
        if (! Schema::hasTable('titan_theme_tokens')) {
            return;
        }

        DB::table('titan_theme_tokens')->updateOrInsert(
            [
                'panel' => 'global',
                'scope' => 'responsive:tables',
                'key' => 'resource_tables',
            ],
            [
                'value' => json_encode($this->responsiveTableColumns, JSON_THROW_ON_ERROR),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }

    private function sanitizeResponsiveTokenValue(string $token, string $value): string
    {
        $value = trim($value);

        if (preg_match('/^-?\d+(?:\.\d+)?(px|rem|em|%)$/', $value) === 1) {
            return $value;
        }

        $definition = $this->responsiveTokenDefinitions()[$token] ?? null;

        return (string) ($definition[$this->activeResponsiveBreakpoint] ?? $definition['desktop'] ?? '0px');
    }

    public function previewPanelOptions(): array
    {
        $panels = config('titan_panels.panels', []);
        $user = auth()->user();

        if (! $user) {
            return [];
        }

        if (method_exists($user, 'hasRole') && $user->hasRole('super_admin')) {
            return $panels;
        }

        return array_filter(
            $panels,
            fn (array $panel): bool => empty($panel['roles']) || $user->hasAnyRole($panel['roles'])
        );
    }

    public function previewPanelUrl(): string
    {
        if ($this->previewFrameSize === 'customer') {
            $panels = $this->previewPanelOptions();
            $customerPanel = $panels['zerofuss'] ?? null;
            $path = trim((string) ($customerPanel['path'] ?? 'zerofuss'), '/');

            return $path === '' ? url('/') : url('/' . $path);
        }

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
        $breakpoint = $this->breakpointForPreviewMode($this->previewFrameSize);
        $definitions = $this->responsiveTokenDefinitions();
        $responsive = [];

        foreach ($definitions as $token => $definition) {
            $responsive[$token] = $this->responsiveTokens[$breakpoint][$token]
                ?? $definition[$breakpoint]
                ?? $definition['desktop']
                ?? '0px';
        }

        if ($this->previewFrameSize === 'collapsed') {
            $responsive['--sidebar-width'] = '64px';
        }

        return [
            '--color-primary-500' => $this->safeColor($this->primaryColor),
            '--color-secondary-500' => $this->safeColor($this->secondaryColor),
            '--color-accent-500' => $this->safeColor($this->accentColor),
            '--color-surface-50' => $this->safeColor($this->surfaceColor),
            '--font-family' => $this->safeFont($this->fontFamily),
            ...$responsive,
        ];
    }

    public function responsivePreviewPayload(): array
    {
        return [
            'mode' => $this->previewFrameSize,
            'breakpoint' => $this->breakpointForPreviewMode($this->previewFrameSize),
            'width' => $this->previewFrameWidth(),
            'tableColumns' => $this->responsiveTableColumns,
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
    // Marketplace actions
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Apply a built-in curated theme by its key (e.g. 'ocean', 'aurora').
     */
    public function applyBuiltinTheme(string $key): void
    {
        $themes = ThemePackManager::builtinThemes();

        if (! isset($themes[$key])) {
            Notification::make()->title('Unknown theme')->warning()->send();

            return;
        }

        $this->createThemeVersion('Auto snapshot before preset switch');

        $tokens = $themes[$key]['tokens'];

        $this->primaryColor   = $tokens['primary_color']   ?? $this->primaryColor;
        $this->secondaryColor = $tokens['secondary_color'] ?? $this->secondaryColor;
        $this->accentColor    = $tokens['accent_color']    ?? $this->accentColor;
        $this->surfaceColor   = $tokens['surface_color']   ?? $this->surfaceColor;
        $this->fontFamily     = $tokens['font_heading']    ?? $this->fontFamily;
        $this->fontHeading    = $this->fontFamily;
        $this->fontBody       = $this->fontFamily;

        Notification::make()
            ->title("Theme \"{$themes[$key]['name']}\" applied")
            ->body('Click Publish to save the changes.')
            ->success()
            ->send();
    }

    /**
     * Validate an uploaded ZIP and store the preview info.
     * Called when a file is selected in the Install tab.
     */
    public function previewZip(): void
    {
        $this->validate(['themeZipUpload' => 'required|file|mimes:zip|max:10240']);

        $manager = new ThemePackManager();
        $result  = $manager->validateZip($this->themeZipUpload->getRealPath());

        if (! $result['ok']) {
            Notification::make()->title('Invalid theme pack')->body($result['error'])->danger()->send();
            $this->zipPreview = [];

            return;
        }

        $this->zipPreview = [
            'meta'   => $result['meta'],
            'tokens' => $result['tokens'],
        ];

        Notification::make()->title('Theme pack validated')->success()->send();
    }

    /**
     * Apply the previously validated ZIP theme preview to the branding state.
     */
    public function installFromZip(): void
    {
        if (empty($this->zipPreview['tokens'])) {
            Notification::make()->title('Upload and validate a theme pack first.')->warning()->send();

            return;
        }

        $this->createThemeVersion('Auto snapshot before preset switch');

        $tokens = $this->zipPreview['tokens'];

        $this->primaryColor   = $tokens['primary_color']   ?? $this->primaryColor;
        $this->secondaryColor = $tokens['secondary_color'] ?? $this->secondaryColor;
        $this->accentColor    = $tokens['accent_color']    ?? $this->accentColor;
        $this->surfaceColor   = $tokens['surface_color']   ?? $this->surfaceColor;
        $this->fontFamily     = $tokens['font_heading']    ?? $this->fontFamily;
        $this->fontHeading    = $this->fontFamily;
        $this->fontBody       = $this->fontFamily;

        $this->themeZipUpload = null;
        $this->zipPreview     = [];

        Notification::make()
            ->title('Theme installed — click Publish to save.')
            ->success()
            ->send();
    }

    /**
     * Stream a ZIP of the current theme as a file download.
     */
    public function exportTheme(): mixed
    {
        $settings = PlatformSetting::current();
        $name     = $settings->brandName();

        $tokens = array_filter([
            'primary_color'   => $this->primaryColor,
            'secondary_color' => $this->secondaryColor,
            'accent_color'    => $this->accentColor,
            'surface_color'   => $this->surfaceColor,
            'font_heading'    => $this->fontHeading ?: $this->fontFamily,
            'font_body'       => $this->fontBody ?: $this->fontFamily,
        ]);

        try {
            $manager = new ThemePackManager();
            $tmpPath = $manager->buildExportZip($name, $tokens);
        } catch (\RuntimeException $e) {
            Notification::make()->title($e->getMessage())->danger()->send();

            return null;
        }

        $fileName = Str::slug($name) . '-theme.zip';

        return response()->download($tmpPath, $fileName, [
            'Content-Type' => 'application/zip',
        ])->deleteFileAfterSend();
    }

    /**
     * Store the current theme tokens as a shared link and expose the URL.
     */
    public function shareTheme(): void
    {
        if (! Schema::hasTable('shared_themes')) {
            Notification::make()
                ->title('Run migrations first')
                ->body('The shared_themes table does not exist yet.')
                ->warning()
                ->send();

            return;
        }

        $name = trim($this->shareThemeName) ?: PlatformSetting::current()->brandName();

        $tokens = array_filter([
            'primary_color'   => $this->primaryColor,
            'secondary_color' => $this->secondaryColor,
            'accent_color'    => $this->accentColor,
            'surface_color'   => $this->surfaceColor,
            'font_heading'    => $this->fontHeading ?: $this->fontFamily,
            'font_body'       => $this->fontBody ?: $this->fontFamily,
        ]);

        $manager = new ThemePackManager();
        $token   = $manager->createShareToken(
            $name,
            auth()->user()?->name,
            $tokens
        );

        $this->generatedShareUrl = url('/theme/import/' . $token);

        Notification::make()
            ->title('Share link generated')
            ->body('Copy the URL below and send it to anyone.')
            ->success()
            ->send();
    }

    /**
     * Fetch and preview a theme from a share URL.
     */
    public function previewImport(): void
    {
        $url = trim($this->importUrl);

        if ($url === '') {
            Notification::make()->title('Enter a share URL first.')->warning()->send();

            return;
        }

        // Extract the token from the URL
        $token = basename(parse_url($url, PHP_URL_PATH) ?? '');

        if ($token === '') {
            Notification::make()->title('Invalid share URL.')->danger()->send();

            return;
        }

        if (! Schema::hasTable('shared_themes')) {
            Notification::make()->title('Run migrations first.')->warning()->send();

            return;
        }

        $manager = new ThemePackManager();
        $data    = $manager->resolveShareToken($token);

        if (! $data) {
            Notification::make()->title('Theme not found or link has expired.')->danger()->send();
            $this->importPreview = [];

            return;
        }

        $this->importPreview = $data;

        Notification::make()->title('Theme preview loaded.')->success()->send();
    }

    /**
     * Apply the previewed import theme to the branding state.
     */
    public function installFromUrl(): void
    {
        if (empty($this->importPreview['tokens'])) {
            Notification::make()->title('Preview a theme first.')->warning()->send();

            return;
        }

        $this->createThemeVersion('Auto snapshot before preset switch');

        $tokens = $this->importPreview['tokens'];

        $this->primaryColor   = $tokens['primary_color']   ?? $this->primaryColor;
        $this->secondaryColor = $tokens['secondary_color'] ?? $this->secondaryColor;
        $this->accentColor    = $tokens['accent_color']    ?? $this->accentColor;
        $this->surfaceColor   = $tokens['surface_color']   ?? $this->surfaceColor;
        $this->fontFamily     = $tokens['font_heading']    ?? $this->fontFamily;
        $this->fontHeading    = $this->fontFamily;
        $this->fontBody       = $this->fontFamily;

        $this->importUrl     = '';
        $this->importPreview = [];

        Notification::make()
            ->title('Theme installed — click Publish to save.')
            ->success()
            ->send();
    }

    public function saveNamedSnapshot(): void
    {
        $label = trim($this->versionLabel);

        if ($label === '') {
            Notification::make()->title('Enter a snapshot label first.')->warning()->send();

            return;
        }

        $version = $this->createThemeVersion($label);

        if (! $version) {
            Notification::make()->title('Theme version table not available.')->warning()->send();

            return;
        }

        $this->versionLabel = '';
        $this->loadThemeVersions();

        Notification::make()
            ->title("Snapshot saved as v{$version->version_number}")
            ->success()
            ->send();
    }

    public function rollbackThemeVersion(int $versionNumber): void
    {
        $orgId = auth()->user()?->organization_id;

        if (! $orgId || ! Schema::hasTable('titan_theme_versions')) {
            Notification::make()->title('Theme versions unavailable.')->warning()->send();

            return;
        }

        $version = TitanThemeVersion::query()
            ->where('org_id', $orgId)
            ->where('panel', $this->resolveCurrentPanelId())
            ->where('version_number', $versionNumber)
            ->first();

        if (! $version || ! is_array($version->token_snapshot)) {
            Notification::make()->title('Requested version not found.')->warning()->send();

            return;
        }

        $this->applyThemeVersionSnapshot($version->token_snapshot);
        $this->versionLabel = "Rollback from v{$versionNumber}";
        $this->publish();
    }

    public function refreshVersionDiff(): void
    {
        $this->versionDiffRows = [];

        if (! $this->diffFromVersion || ! $this->diffToVersion) {
            return;
        }

        $orgId = auth()->user()?->organization_id;
        if (! $orgId || ! Schema::hasTable('titan_theme_versions')) {
            return;
        }

        $versions = TitanThemeVersion::query()
            ->where('org_id', $orgId)
            ->where('panel', $this->resolveCurrentPanelId())
            ->whereIn('version_number', [$this->diffFromVersion, $this->diffToVersion])
            ->get()
            ->keyBy('version_number');

        $left = $this->flattenThemeSnapshot((array) ($versions[$this->diffFromVersion]->token_snapshot ?? []));
        $right = $this->flattenThemeSnapshot((array) ($versions[$this->diffToVersion]->token_snapshot ?? []));

        $keys = array_values(array_unique(array_merge(array_keys($left), array_keys($right))));
        sort($keys);

        foreach ($keys as $key) {
            $leftValue = (string) ($left[$key] ?? '');
            $rightValue = (string) ($right[$key] ?? '');
            $this->versionDiffRows[] = [
                'token' => $key,
                'left' => $leftValue,
                'right' => $rightValue,
                'changed' => $leftValue !== $rightValue,
            ];
        }
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
            'accentColor' => ['required', 'regex:'.self::HEX_COLOR_REGEX],
            'surfaceColor' => ['required', 'regex:'.self::HEX_COLOR_REGEX],
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
            app(ThemeTokenManager::class)->savePlatformThemeTokens($settings, [
                'primary_color' => $validated['primaryColor'],
                'secondary_color' => $validated['secondaryColor'],
                'accent_color' => $validated['accentColor'],
                'surface_color' => $validated['surfaceColor'],
                'font_heading' => $validated['fontFamily'] ?: 'Figtree',
                'font_body' => $validated['fontFamily'] ?: 'Figtree',
            ]);

            app(ThemeTokenManager::class)->saveResponsiveOverrides(
                $this->responsiveTokens,
                $this->responsiveTokenDefinitions()
            );


            $this->saveResponsiveTableColumns();
        }

        // Persist shared theme settings for app shell preview behavior.
        $settings->update([
            'accent_color' => $this->accentColor,
            'surface_color' => $this->surfaceColor,
        ]);
        cache()->forget('platform_settings');

        // Persist dashboard layout fallback row for legacy dashboard consumers.
        if (Schema::hasTable('layouts')) {
            $slug = 'ui-studio-layout';
            $userId = (int) (auth()->id() ?? DB::table('users')->min('id') ?? 1);
            $widgets = array_map(fn ($w) => [
                'type' => $w['type'],
                'data' => array_merge(
                    ['title' => $w['label']],
                    $w['properties'] ?? [],
                ),
            ], $this->canvasWidgets);

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

        // 4. Persist role UI profiles
        $orgId = auth()->user()?->organization_id;
        if ($orgId && Schema::hasTable('role_ui_profiles')) {
            foreach ($this->roleProfiles as $role => $data) {
                if (! array_key_exists($role, RoleUIProfile::SUPPORTED_ROLES)) {
                    continue;
                }
                RoleUIProfile::withoutGlobalScope(\App\Models\Scopes\TenantScope::class)
                    ->updateOrCreate(
                        ['organization_id' => $orgId, 'role' => $role],
                        [
                            'primary_color'    => $this->safeColor($data['primary_color'] ?? '', '') ?: null,
                            'secondary_color'  => $this->safeColor($data['secondary_color'] ?? '', '') ?: null,
                            'accent_color'     => $this->safeColor($data['accent_color'] ?? '', '') ?: null,
                            'surface_color'    => $this->safeColor($data['surface_color'] ?? '', '') ?: null,
                            'hidden_nav_items' => $data['hidden_nav_items'] ?? [],
                            'widget_layout'    => $data['widget_layout'] ?? [],
                        ]
                    );
                cache()->forget("role_ui_profile.{$orgId}.{$role}");
            }
        }

        $label = trim($this->versionLabel);
        $this->createThemeVersion($label !== '' ? $label : 'Manual save');
        $this->versionLabel = '';

        Notification::make()
            ->title('UI Studio layout published')
            ->body('Branding, dashboard layout, menu, and role profile changes have been saved.')
            ->success()
            ->send();

        $this->savedThemeSnapshot = $this->themeSnapshot();
        $this->loadThemeVersions();
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Helpers
    // ─────────────────────────────────────────────────────────────────────────

    private function loadThemeVersions(): void
    {
        $this->themeVersions = [];
        $this->diffFromVersion = null;
        $this->diffToVersion = null;
        $this->versionDiffRows = [];

        $orgId = auth()->user()?->organization_id;
        if (! $orgId || ! Schema::hasTable('titan_theme_versions')) {
            return;
        }

        $rows = TitanThemeVersion::query()
            ->with('author:id,name')
            ->where('org_id', $orgId)
            ->where('panel', $this->resolveCurrentPanelId())
            ->orderByDesc('version_number')
            ->get();

        $this->themeVersions = $rows->map(static fn (TitanThemeVersion $version): array => [
            'version_number' => (int) $version->version_number,
            'label' => (string) ($version->label ?? "v{$version->version_number}"),
            'created_at' => (string) optional($version->created_at)?->format('Y-m-d H:i'),
            'created_by' => (string) ($version->author?->name ?? 'System'),
        ])->values()->all();

        if (count($this->themeVersions) >= 2) {
            $this->diffFromVersion = $this->themeVersions[1]['version_number'];
            $this->diffToVersion = $this->themeVersions[0]['version_number'];
            $this->refreshVersionDiff();
        }
    }

    private function createThemeVersion(?string $label = null): ?TitanThemeVersion
    {
        $orgId = auth()->user()?->organization_id;

        if (! $orgId || ! Schema::hasTable('titan_theme_versions')) {
            return null;
        }

        $version = TitanThemeVersion::createSnapshot(
            (int) $orgId,
            $this->resolveCurrentPanelId(),
            $this->themeVersionSnapshot(),
            $label,
            (int) (auth()->id() ?? 0) ?: null
        );

        $this->loadThemeVersions();

        return $version;
    }

    private function themeVersionSnapshot(): array
    {
        return [
            'panel_name' => $this->panelName,
            'primary_color' => $this->primaryColor,
            'secondary_color' => $this->secondaryColor,
            'accent_color' => $this->accentColor,
            'surface_color' => $this->surfaceColor,
            'font_family' => $this->fontFamily,
            'font_heading' => $this->fontHeading,
            'font_body' => $this->fontBody,
            'background_type' => $this->backgroundType,
            'background_value' => $this->backgroundValue,
            'menu_items' => $this->menuItems,
            'dashboard_layout' => $this->canvasWidgets,
            'responsive_tokens' => $this->responsiveTokens,
            'responsive_table_columns' => $this->responsiveTableColumns,
        ];
    }

    private function applyThemeVersionSnapshot(array $snapshot): void
    {
        $this->panelName = (string) ($snapshot['panel_name'] ?? $this->panelName);
        $this->primaryColor = $this->safeColor((string) ($snapshot['primary_color'] ?? $this->primaryColor), $this->primaryColor);
        $this->secondaryColor = $this->safeColor((string) ($snapshot['secondary_color'] ?? $this->secondaryColor), $this->secondaryColor);
        $this->accentColor = $this->safeColor((string) ($snapshot['accent_color'] ?? $this->accentColor), $this->accentColor);
        $this->surfaceColor = $this->safeColor((string) ($snapshot['surface_color'] ?? $this->surfaceColor), $this->surfaceColor);
        $this->fontFamily = $this->safeFont((string) ($snapshot['font_family'] ?? $this->fontFamily), $this->fontFamily);
        $this->fontHeading = $this->safeFont((string) ($snapshot['font_heading'] ?? $this->fontHeading), $this->fontHeading);
        $this->fontBody = $this->safeFont((string) ($snapshot['font_body'] ?? $this->fontBody), $this->fontBody);
        $this->backgroundType = (string) ($snapshot['background_type'] ?? $this->backgroundType);
        $this->backgroundValue = isset($snapshot['background_value']) ? (string) $snapshot['background_value'] : $this->backgroundValue;
        $this->menuItems = is_array($snapshot['menu_items'] ?? null) ? $snapshot['menu_items'] : $this->menuItems;
        $this->canvasWidgets = is_array($snapshot['dashboard_layout'] ?? null) ? $snapshot['dashboard_layout'] : $this->canvasWidgets;
        $this->responsiveTokens = is_array($snapshot['responsive_tokens'] ?? null) ? $snapshot['responsive_tokens'] : $this->responsiveTokens;
        $this->responsiveTableColumns = is_array($snapshot['responsive_table_columns'] ?? null) ? $snapshot['responsive_table_columns'] : $this->responsiveTableColumns;
    }

    /**
     * @return array<string, scalar>
     */
    private function flattenThemeSnapshot(array $snapshot, string $prefix = ''): array
    {
        $flattened = [];

        foreach ($snapshot as $key => $value) {
            $currentKey = $prefix === '' ? (string) $key : "{$prefix}.{$key}";

            if (is_array($value)) {
                $flattened += $this->flattenThemeSnapshot($value, $currentKey);

                continue;
            }

            if (is_scalar($value) || $value === null) {
                $flattened[$currentKey] = $value ?? '';
            }
        }

        return $flattened;
    }

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
            array_map(function (array $w, int $i) {
                $type       = $w['type'] ?? 'html-card';
                $savedData  = is_array($w['data'] ?? null) ? $w['data'] : [];

                // Merge registry defaults with saved data so the editor always has
                // a complete set of keys even when new fields are added later.
                // 'title' is intentionally excluded because it is stored as the
                // widget's 'label' field, not inside the properties array.
                $defaults   = WidgetPropertyRegistry::defaults($type);
                $properties = array_merge($defaults, array_diff_key($savedData, ['title' => true]));

                return [
                    'id'         => 'w_' . Str::ulid(),
                    'type'       => $type,
                    'label'      => $savedData['title'] ?? ucwords(str_replace(['-', '_'], ' ', $type)),
                    'columns'    => $w['columns'] ?? 12,
                    'order'      => $i,
                    'properties' => $properties,
                ];
            }, $widgets, array_keys($widgets))
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
     * Load persisted role UI profiles for the current organisation, keyed by
     * role slug.  Returns default structures for roles that have no saved record.
     *
     * @return array<string, array<string, mixed>>
     */
    private function loadRoleProfiles(): array
    {
        $profiles = [];

        if (! Schema::hasTable('role_ui_profiles')) {
            return $profiles;
        }

        $orgId = auth()->user()?->organization_id;

        if (! $orgId) {
            return $profiles;
        }

        $rows = RoleUIProfile::withoutGlobalScope(\App\Models\Scopes\TenantScope::class)
            ->where('organization_id', $orgId)
            ->get();

        foreach ($rows as $row) {
            $profiles[$row->role] = [
                'primary_color'    => $row->primary_color    ?? '',
                'secondary_color'  => $row->secondary_color  ?? '',
                'accent_color'     => $row->accent_color     ?? '',
                'surface_color'    => $row->surface_color    ?? '',
                'hidden_nav_items' => $row->hidden_nav_items ?? [],
                'widget_layout'    => $row->widget_layout    ?? [],
            ];
        }

        return $profiles;
    }

    /** @return array<string, mixed> */
    private function defaultRoleProfile(): array
    {
        return [
            'primary_color'    => '',
            'secondary_color'  => '',
            'accent_color'     => '',
            'surface_color'    => '',
            'hidden_nav_items' => [],
            'widget_layout'    => [],
        ];
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

    /**
     * Populate $widgetPropertyValues from the canvas widget matching $id.
     * Registry defaults fill in any keys not yet saved.
     */
    private function loadWidgetPropertyValues(string $id): void
    {
        foreach ($this->canvasWidgets as $widget) {
            if ($widget['id'] !== $id) {
                continue;
            }

            $type     = $widget['type'] ?? '';
            $saved    = is_array($widget['properties'] ?? null) ? $widget['properties'] : [];
            $defaults = WidgetPropertyRegistry::defaults($type);

            $this->widgetPropertyValues = array_merge($defaults, $saved);

            return;
        }

        $this->widgetPropertyValues = [];
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
            'responsiveTokens' => json_encode($this->responsiveTokens),
            'responsiveTableColumns' => json_encode($this->responsiveTableColumns),
        ];
    }
}
