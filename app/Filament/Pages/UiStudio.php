<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Filament\Pages\UiStudio\WidgetPropertyRegistry;
use App\Models\AiThemeSnapshot;
use App\Models\OrganizationBranding;
use App\Models\PlatformSetting;
use App\Models\RoleUIProfile;
use App\Models\SharedTheme;
use App\Services\AiThemeGenerator;
use App\Support\OrganizationBrandingResolver;
use App\Support\ThemeTokenManager;
use App\Support\ThemePackManager;
use App\Support\ThemeExportManager;
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
    public string $fontCode       = 'JetBrains Mono';
    public string $fontScaleBase  = '14';
    public string $fontScaleRatio = '1.25';
    public string $fontWeightHeading = '600';
    public string $fontWeightBody = '400';
    public string $lineHeightBody = '1.6';
    public string $letterSpacingHeading = '-0.01';
    public string $typographyPreset = 'enterprise';
    public string $googleFontQuery = '';
    public string $googleFontTarget = 'heading';
    public string $fontSourceUrl = '';
    public ?string $customFontPath = null;
    public ?string $customFontFamily = null;
    public ?string $logoPath      = null;
    public ?string $faviconPath   = null;
    public TemporaryUploadedFile|null $logoUpload = null;
    public TemporaryUploadedFile|null $faviconUpload = null;
    public TemporaryUploadedFile|null $customFontUpload = null;
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

    public string $activeTab = 'branding'; // branding | typography | layout | menu | roles | components | marketplace | export

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

    /** Selected export output format for the Export tab. */
    public string $exportFormat = ThemeExportManager::FORMAT_THEME_ZIP;

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
        $this->fontSourceUrl  = $settings->font_source_url ?? '';
        $this->customFontPath = $settings->font_path ?? null;
        $this->customFontFamily = is_string($settings->font_heading) ? $settings->font_heading : null;
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
        $this->loadTypographyTokens();

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

    public function updatedFontBody(string $value): void
    {
        $this->fontFamily = $this->safeFont($value);
    }

    public function typographyPresets(): array
    {
        return [
            'enterprise' => ['label' => 'Enterprise', 'heading' => 'Inter', 'body' => 'Inter', 'code' => 'JetBrains Mono'],
            'editorial' => ['label' => 'Editorial', 'heading' => 'Playfair Display', 'body' => 'Source Serif 4', 'code' => 'JetBrains Mono'],
            'compact' => ['label' => 'Compact', 'heading' => 'DM Sans', 'body' => 'DM Sans', 'code' => 'DM Mono'],
            'code-forward' => ['label' => 'Code-forward', 'heading' => 'JetBrains Mono', 'body' => 'Inter', 'code' => 'JetBrains Mono'],
            'luxury' => ['label' => 'Luxury', 'heading' => 'Cormorant Garamond', 'body' => 'Jost', 'code' => 'JetBrains Mono'],
        ];
    }

    public function availableGoogleFonts(): array
    {
        return [
            'Inter', 'JetBrains Mono', 'Playfair Display', 'Source Serif 4', 'DM Sans', 'DM Mono', 'Cormorant Garamond',
            'Jost', 'Roboto', 'Lato', 'Open Sans', 'Montserrat', 'Merriweather', 'Poppins', 'Nunito', 'Fira Sans', 'Inconsolata',
            'Space Grotesk', 'IBM Plex Sans', 'IBM Plex Mono', 'Work Sans', 'Manrope', 'PT Serif', 'Bebas Neue', 'Oswald', 'Raleway',
        ];
    }

    public function filteredGoogleFonts(): array
    {
        $query = strtolower(trim($this->googleFontQuery));
        $fonts = $this->availableGoogleFonts();

        if ($query !== '') {
            $fonts = array_values(array_filter($fonts, static fn (string $font): bool => str_contains(strtolower($font), $query)));

            if ($fonts === [] && preg_match('/^[A-Za-z0-9\s-]+$/', $this->googleFontQuery) === 1) {
                $fonts[] = trim($this->googleFontQuery);
            }
        }

        return $fonts;
    }

    public function applyTypographyPreset(string $preset): void
    {
        $definition = $this->typographyPresets()[$preset] ?? null;

        if (! is_array($definition)) {
            return;
        }

        $this->typographyPreset = $preset;
        $this->fontHeading = $definition['heading'];
        $this->fontBody = $definition['body'];
        $this->fontCode = $definition['code'];
        $this->fontFamily = $definition['body'];
        $this->fontSourceUrl = $this->googleFontStylesheetUrl([$this->fontHeading, $this->fontBody, $this->fontCode]);
        $this->customFontPath = null;
        $this->customFontFamily = null;
    }

    public function applyGoogleFont(string $font): void
    {
        $font = $this->safeFont($font, '');

        if ($font === '') {
            return;
        }

        if ($this->googleFontTarget === 'body') {
            $this->fontBody = $font;
            $this->fontFamily = $font;
        } else {
            $this->fontHeading = $font;
        }

        $this->fontSourceUrl = $this->googleFontStylesheetUrl([$this->fontHeading, $this->fontBody, $this->fontCode]);
        $this->customFontPath = null;
        $this->customFontFamily = null;
    }

    public function uploadCustomFont(): void
    {
        $this->validate(['customFontUpload' => 'required|file|mimes:woff2|max:4096']);

        $orgId = auth()->user()?->organization_id;

        if (! $orgId) {
            Notification::make()->title('No organisation context')->body('Custom font upload requires an organisation.')->warning()->send();

            return;
        }

        $path = $this->customFontUpload?->store($this->brandingDirectory($orgId) . '/fonts', 'public');

        if (! $path) {
            return;
        }

        $customFontName = trim($this->googleFontQuery) !== '' ? $this->safeFont($this->googleFontQuery, 'Custom Org Font') : 'Custom Org Font';

        if ($this->customFontPath && $this->customFontPath !== $path) {
            Storage::disk('public')->delete($this->customFontPath);
        }

        $this->customFontPath = $path;
        $this->customFontFamily = $customFontName;
        $this->fontHeading = $customFontName;
        $this->fontBody = $customFontName;
        $this->fontFamily = $customFontName;
        $this->fontSourceUrl = '';
        $this->customFontUpload = null;

        Notification::make()->title('Custom font uploaded')->success()->send();
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

        $scale = $this->generateTypeScale();

        return [
            '--color-primary-500' => $this->safeColor($this->primaryColor),
            '--color-secondary-500' => $this->safeColor($this->secondaryColor),
            '--color-accent-500' => $this->safeColor($this->accentColor),
            '--color-surface-50' => $this->safeColor($this->surfaceColor),
            '--font-family' => $this->safeFont($this->fontFamily),
            '--font-heading' => $this->safeFont($this->fontHeading),
            '--font-body' => $this->safeFont($this->fontBody),
            '--font-code' => $this->safeFont($this->fontCode, 'JetBrains Mono'),
            '--font-scale-base' => $this->sanitizeFontScaleBase($this->fontScaleBase) . 'px',
            '--font-scale-ratio' => (string) $this->sanitizeFontScaleRatio($this->fontScaleRatio),
            '--font-weight-heading' => (string) $this->sanitizeFontWeight($this->fontWeightHeading, '600'),
            '--font-weight-body' => (string) $this->sanitizeFontWeight($this->fontWeightBody, '400'),
            '--line-height-body' => (string) $this->sanitizeLineHeight($this->lineHeightBody),
            '--letter-spacing-heading' => (string) $this->sanitizeLetterSpacing($this->letterSpacingHeading) . 'em',
            '--font-size-sm' => $scale['sm'],
            '--font-size-base' => $scale['base'],
            '--font-size-lg' => $scale['lg'],
            '--font-size-xl' => $scale['xl'],
            '--font-size-2xl' => $scale['2xl'],
            '--font-size-3xl' => $scale['3xl'],
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

    public function installedMarketplaceThemes(): array
    {
        $orgId = auth()->user()?->organization_id;

        if (! $orgId || ! Schema::hasTable('organization_brandings')) {
            return [];
        }

        $branding = OrganizationBranding::withoutGlobalScopes()
            ->where('organization_id', $orgId)
            ->first();

        if (! $branding) {
            return [];
        }

        return array_map(static function (array $theme): array {
            return [
                'slug' => (string) ($theme['slug'] ?? ''),
                'name' => (string) ($theme['name'] ?? 'Installed Theme'),
                'author' => (string) ($theme['author'] ?? 'Unknown'),
                'version' => (string) ($theme['version'] ?? '1.0.0'),
                'tags' => is_array($theme['tags'] ?? null) ? $theme['tags'] : [],
                'tokens' => is_array($theme['tokens'] ?? null) ? $theme['tokens'] : [],
            ];
        }, $branding->installedThemePacks());
    }

    public function applyInstalledTheme(string $slug): void
    {
        $theme = collect($this->installedMarketplaceThemes())
            ->first(static fn (array $installedTheme): bool => ($installedTheme['slug'] ?? '') === Str::slug($slug));

        if (! is_array($theme) || ! is_array($theme['tokens'] ?? null) || $theme['tokens'] === []) {
            Notification::make()->title('Installed theme not found')->warning()->send();

            return;
        }

        $tokens = $theme['tokens'];

        $this->primaryColor   = $tokens['primary_color']   ?? $this->primaryColor;
        $this->secondaryColor = $tokens['secondary_color'] ?? $this->secondaryColor;
        $this->accentColor    = $tokens['accent_color']    ?? $this->accentColor;
        $this->surfaceColor   = $tokens['surface_color']   ?? $this->surfaceColor;
        $this->fontFamily     = $tokens['font_heading']    ?? $this->fontFamily;
        $this->fontHeading    = $this->fontFamily;
        $this->fontBody       = $this->fontFamily;

        Notification::make()
            ->title("Theme \"{$theme['name']}\" applied")
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

        $tokens = $this->zipPreview['tokens'];

        $this->primaryColor   = $tokens['primary_color']   ?? $this->primaryColor;
        $this->secondaryColor = $tokens['secondary_color'] ?? $this->secondaryColor;
        $this->accentColor    = $tokens['accent_color']    ?? $this->accentColor;
        $this->surfaceColor   = $tokens['surface_color']   ?? $this->surfaceColor;
        $this->fontFamily     = $tokens['font_heading']    ?? $this->fontFamily;
        $this->fontHeading    = $this->fontFamily;
        $this->fontBody       = $this->fontFamily;
        $this->installThemePackForOrganization(
            is_array($this->zipPreview['meta'] ?? null) ? $this->zipPreview['meta'] : [],
            $tokens
        );

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
        return $this->downloadExport(ThemeExportManager::FORMAT_THEME_ZIP);
    }

    /** @return array<string, string> */
    public function exportFormatOptions(): array
    {
        return app(ThemeExportManager::class)->formats();
    }

    public function exportSelection(): mixed
    {
        return $this->downloadExport($this->exportFormat);
    }

    private function downloadExport(string $format): mixed
    {
        $name = PlatformSetting::current()->brandName();
        $organizationId = auth()->user()?->organization_id;

        try {
            $export = app(ThemeExportManager::class)->export($name, $format, $organizationId);
        } catch (\Throwable $exception) {
            Notification::make()->title($exception->getMessage())->danger()->send();

            return null;
        }

        return response()->download($export['path'], $export['fileName'], [
            'Content-Type' => $export['contentType'],
        ])->deleteFileAfterSend();
    }

    /** @param  array<string, mixed>  $meta  @param  array<string, mixed>  $tokens */
    private function installThemePackForOrganization(array $meta, array $tokens): void
    {
        $orgId = auth()->user()?->organization_id;

        if (! $orgId || ! Schema::hasTable('organization_brandings')) {
            return;
        }

        $name = trim((string) ($meta['name'] ?? ''));
        $author = trim((string) ($meta['author'] ?? ''));
        $version = trim((string) ($meta['version'] ?? ''));
        $slug = Str::slug((string) ($meta['slug'] ?? $name));
        $tags = $meta['tags'] ?? [];

        $branding = OrganizationBranding::withoutGlobalScopes()->firstOrCreate(['organization_id' => $orgId]);
        $branding->installThemePack([
            'slug' => $slug !== '' ? $slug : 'installed-theme',
            'name' => $name !== '' ? $name : 'Installed Theme',
            'author' => $author !== '' ? $author : 'Unknown',
            'version' => $version !== '' ? $version : '1.0.0',
            'tags' => is_array($tags) ? array_values(array_filter($tags, static fn (mixed $tag): bool => is_string($tag))) : [],
            'tokens' => $tokens,
        ]);
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

        $tokens = $this->importPreview['tokens'];

        $this->primaryColor   = $tokens['primary_color']   ?? $this->primaryColor;
        $this->secondaryColor = $tokens['secondary_color'] ?? $this->secondaryColor;
        $this->accentColor    = $tokens['accent_color']    ?? $this->accentColor;
        $this->surfaceColor   = $tokens['surface_color']   ?? $this->surfaceColor;
        $this->fontFamily     = $tokens['font_heading']    ?? $this->fontFamily;
        $this->fontHeading    = $this->fontFamily;
        $this->fontBody       = $this->fontFamily;
        $this->installThemePackForOrganization(
            is_array($this->importPreview['meta'] ?? null) ? $this->importPreview['meta'] : [],
            $tokens
        );

        $this->importUrl     = '';
        $this->importPreview = [];

        Notification::make()
            ->title('Theme installed — click Publish to save.')
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
            'accentColor' => ['required', 'regex:'.self::HEX_COLOR_REGEX],
            'surfaceColor' => ['required', 'regex:'.self::HEX_COLOR_REGEX],
            'fontFamily' => ['nullable', 'regex:/^[\w\s\-]+$/', 'max:120'],
            'fontHeading' => ['nullable', 'regex:/^[\w\s\-]+$/', 'max:120'],
            'fontBody' => ['nullable', 'regex:/^[\w\s\-]+$/', 'max:120'],
            'fontCode' => ['nullable', 'regex:/^[\w\s\-]+$/', 'max:120'],
            'fontScaleBase' => ['required', 'numeric', 'min:10', 'max:24'],
            'fontScaleRatio' => ['required', 'numeric', 'min:1.05', 'max:1.8'],
            'fontWeightHeading' => ['required', 'integer', 'min:300', 'max:900'],
            'fontWeightBody' => ['required', 'integer', 'min:300', 'max:900'],
            'lineHeightBody' => ['required', 'numeric', 'min:1.1', 'max:2.2'],
            'letterSpacingHeading' => ['required', 'numeric', 'min:-0.08', 'max:0.2'],
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
                'font_family' => $validated['fontBody'] ?: ($validated['fontFamily'] ?: null),
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
                'font_heading' => $validated['fontHeading'] ?: ($validated['fontFamily'] ?: 'Figtree'),
                'font_body' => $validated['fontBody'] ?: ($validated['fontFamily'] ?: 'Figtree'),
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
            'font_heading' => $this->fontHeading ?: 'Figtree',
            'font_body' => $this->fontBody ?: 'Figtree',
            'font_source_url' => $this->fontSourceUrl ?: null,
            'font_path' => $this->customFontPath,
        ]);
        $this->saveTypographyTokens();
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

        Notification::make()
            ->title('UI Studio layout published')
            ->body('Branding, dashboard layout, menu, and role profile changes have been saved.')
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

    /** @return array<string, string> */
    public function generatedTypeScale(): array
    {
        return $this->generateTypeScale();
    }

    /** @return array<string, string> */
    private function generateTypeScale(): array
    {
        $base = $this->sanitizeFontScaleBase($this->fontScaleBase);
        $ratio = $this->sanitizeFontScaleRatio($this->fontScaleRatio);

        return [
            'sm' => number_format(max(10, $base / $ratio), 2, '.', '') . 'px',
            'base' => number_format($base, 2, '.', '') . 'px',
            'lg' => number_format($base * $ratio, 2, '.', '') . 'px',
            'xl' => number_format($base * ($ratio ** 2), 2, '.', '') . 'px',
            '2xl' => number_format($base * ($ratio ** 3), 2, '.', '') . 'px',
            '3xl' => number_format($base * ($ratio ** 4), 2, '.', '') . 'px',
        ];
    }

    private function sanitizeFontScaleBase(string $value): float
    {
        return max(10, min(24, (float) $value));
    }

    private function sanitizeFontScaleRatio(string $value): float
    {
        return max(1.05, min(1.8, (float) $value));
    }

    private function sanitizeFontWeight(string $value, string $fallback): int
    {
        return max(300, min(900, (int) ($value !== '' ? $value : $fallback)));
    }

    private function sanitizeLineHeight(string $value): float
    {
        return max(1.1, min(2.2, (float) $value));
    }

    private function sanitizeLetterSpacing(string $value): float
    {
        return max(-0.08, min(0.2, (float) $value));
    }

    /** @param array<int, string> $families */
    private function googleFontStylesheetUrl(array $families): string
    {
        $families = array_values(array_unique(array_filter(array_map(function (string $font): string {
            return $this->safeFont($font, '');
        }, $families))));

        if ($families === []) {
            return '';
        }

        $query = implode('&family=', array_map(static fn (string $font): string => str_replace(' ', '+', $font), $families));

        return 'https://fonts.googleapis.com/css2?family=' . $query . '&display=swap';
    }

    private function loadTypographyTokens(): void
    {
        if (! Schema::hasTable('titan_theme_tokens')) {
            return;
        }

        $tokens = DB::table('titan_theme_tokens')
            ->where('panel', 'global')
            ->where('scope', 'typography')
            ->pluck('value', 'key')
            ->all();

        $defaults = [
            '--font-heading' => $this->fontHeading,
            '--font-body' => $this->fontBody,
            '--font-code' => $this->fontCode,
            '--font-scale-base' => $this->fontScaleBase,
            '--font-scale-ratio' => $this->fontScaleRatio,
            '--font-weight-heading' => $this->fontWeightHeading,
            '--font-weight-body' => $this->fontWeightBody,
            '--line-height-body' => $this->lineHeightBody,
            '--letter-spacing-heading' => $this->letterSpacingHeading,
            '--font-source-url' => $this->fontSourceUrl,
            '--font-custom-path' => $this->customFontPath ?? '',
            '--font-custom-family' => $this->customFontFamily ?? '',
        ];

        $this->fontHeading = $this->safeFont((string) ($tokens['--font-heading'] ?? $defaults['--font-heading']));
        $this->fontBody = $this->safeFont((string) ($tokens['--font-body'] ?? $defaults['--font-body']));
        $this->fontFamily = $this->fontBody;
        $this->fontCode = $this->safeFont((string) ($tokens['--font-code'] ?? $defaults['--font-code']), 'JetBrains Mono');
        $this->fontScaleBase = (string) $this->sanitizeFontScaleBase((string) ($tokens['--font-scale-base'] ?? $defaults['--font-scale-base']));
        $this->fontScaleRatio = (string) $this->sanitizeFontScaleRatio((string) ($tokens['--font-scale-ratio'] ?? $defaults['--font-scale-ratio']));
        $this->fontWeightHeading = (string) $this->sanitizeFontWeight((string) ($tokens['--font-weight-heading'] ?? $defaults['--font-weight-heading']), '600');
        $this->fontWeightBody = (string) $this->sanitizeFontWeight((string) ($tokens['--font-weight-body'] ?? $defaults['--font-weight-body']), '400');
        $this->lineHeightBody = (string) $this->sanitizeLineHeight((string) ($tokens['--line-height-body'] ?? $defaults['--line-height-body']));
        $this->letterSpacingHeading = (string) $this->sanitizeLetterSpacing((string) ($tokens['--letter-spacing-heading'] ?? $defaults['--letter-spacing-heading']));
        $this->fontSourceUrl = (string) ($tokens['--font-source-url'] ?? $defaults['--font-source-url']);
        $this->customFontPath = (string) ($tokens['--font-custom-path'] ?? $defaults['--font-custom-path']) ?: $this->customFontPath;
        $this->customFontFamily = (string) ($tokens['--font-custom-family'] ?? $defaults['--font-custom-family']) ?: $this->customFontFamily;
    }

    private function saveTypographyTokens(): void
    {
        if (! Schema::hasTable('titan_theme_tokens')) {
            return;
        }

        $rows = [
            ['key' => '--font-heading', 'value' => $this->safeFont($this->fontHeading)],
            ['key' => '--font-body', 'value' => $this->safeFont($this->fontBody)],
            ['key' => '--font-code', 'value' => $this->safeFont($this->fontCode, 'JetBrains Mono')],
            ['key' => '--font-scale-base', 'value' => (string) $this->sanitizeFontScaleBase($this->fontScaleBase)],
            ['key' => '--font-scale-ratio', 'value' => (string) $this->sanitizeFontScaleRatio($this->fontScaleRatio)],
            ['key' => '--font-weight-heading', 'value' => (string) $this->sanitizeFontWeight($this->fontWeightHeading, '600')],
            ['key' => '--font-weight-body', 'value' => (string) $this->sanitizeFontWeight($this->fontWeightBody, '400')],
            ['key' => '--line-height-body', 'value' => (string) $this->sanitizeLineHeight($this->lineHeightBody)],
            ['key' => '--letter-spacing-heading', 'value' => (string) $this->sanitizeLetterSpacing($this->letterSpacingHeading)],
            ['key' => '--font-source-url', 'value' => trim($this->fontSourceUrl)],
            ['key' => '--font-custom-path', 'value' => $this->customFontPath ?? ''],
            ['key' => '--font-custom-family', 'value' => $this->customFontFamily ?? ''],
        ];

        DB::table('titan_theme_tokens')->upsert(
            array_map(static fn (array $row): array => [
                'panel' => 'global',
                'scope' => 'typography',
                'key' => $row['key'],
                'value' => $row['value'],
                'created_at' => now(),
                'updated_at' => now(),
            ], $rows),
            ['panel', 'scope', 'key'],
            ['value', 'updated_at']
        );
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
            'fontHeading' => $this->fontHeading,
            'fontBody' => $this->fontBody,
            'fontCode' => $this->fontCode,
            'fontFamily' => $this->fontFamily,
            'fontScaleBase' => $this->fontScaleBase,
            'fontScaleRatio' => $this->fontScaleRatio,
            'fontWeightHeading' => $this->fontWeightHeading,
            'fontWeightBody' => $this->fontWeightBody,
            'lineHeightBody' => $this->lineHeightBody,
            'letterSpacingHeading' => $this->letterSpacingHeading,
            'fontSourceUrl' => $this->fontSourceUrl,
            'customFontPath' => $this->customFontPath,
            'backgroundType' => $this->backgroundType,
            'backgroundValue' => $this->backgroundValue,
            'customCss' => $this->customCss,
            'responsiveTokens' => json_encode($this->responsiveTokens),
            'responsiveTableColumns' => json_encode($this->responsiveTableColumns),
        ];
    }
}
