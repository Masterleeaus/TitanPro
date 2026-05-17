<?php

namespace App\Filament\Pages;

use App\Models\PlatformSetting;
use App\Support\BrandThemeGenerator;
use App\Support\ThemeTokenManager;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Actions\Action;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;

class SiteSettings extends Page implements HasSchemas
{
    use InteractsWithSchemas;

    private const FONT_UPLOAD_MIME_TYPES = [
        'font/ttf',
        'font/otf',
        'font/woff',
        'font/woff2',
        'application/x-font-ttf',
        'application/font-sfnt',
    ];

    private const MAX_THEME_SNAPSHOTS = 20;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-paint-brush';

    protected static string|\UnitEnum|null $navigationGroup = 'Settings';

    protected static ?int $navigationSort = 10;
    protected static ?string $navigationLabel = 'Site Settings';
    protected string $view = 'filament.pages.site-settings';

    public ?array $data = [];
    public ?array $generatedThemePreview = null;
    public ?string $generatedThemeName = null;

    public function mount(): void
    {
        $settings = PlatformSetting::current();
        $tokenState = app(ThemeTokenManager::class)->semanticEditorState($settings);
        $this->form->fill([
            'app_name' => $settings->app_name,
            'site_name' => $settings->site_name ?: $settings->app_name,
            'logo_path' => $settings->logo_path ?: $settings->logo,
            'favicon_path' => $settings->favicon_path ?: $settings->favicon,
            'primary_color' => $tokenState['primary_color'],
            'secondary_color' => $tokenState['secondary_color'],
            'accent_color' => $tokenState['accent_color'],
            'support_email' => $settings->support_email,
            'billing_email' => $settings->billing_email,
            'contact_phone' => $settings->contact_phone,
            'footer_text' => $settings->footer_text,
            'meta_title' => $settings->meta_title,
            'meta_description' => $settings->meta_description,
            'landing_headline' => $settings->landing_headline,
            'landing_subheadline' => $settings->landing_subheadline,
            'cta_label' => $settings->cta_label,
            'cta_url' => $settings->cta_url,
            'enable_registration' => $settings->enable_registration,
            'maintenance_message' => $settings->maintenance_message,
            'font_heading' => $tokenState['font_heading'],
            'font_body' => $tokenState['font_body'],
            'font_source_url' => $settings->font_source_url,
            'font_path' => $settings->font_path,
            'bg_image_path' => $settings->bg_image_path,
            'surface_color' => $tokenState['surface_color'],
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Branding')->description('Controls the SaaS name, logo, favicon, and semantic design tokens that drive inherited component styling.')->schema([
                Forms\Components\TextInput::make('app_name')->label('Application name')->required()->maxLength(80),
                Forms\Components\TextInput::make('site_name')->label('Public site name')->maxLength(80),
                Forms\Components\FileUpload::make('logo_path')->label('Logo')->disk('public')->directory('platform')->image()->imageEditor()->preserveFilenames()->downloadable()->openable(),
                Forms\Components\FileUpload::make('favicon_path')->label('Favicon')->disk('public')->directory('platform')->image()->preserveFilenames()->downloadable()->openable(),
                Forms\Components\ColorPicker::make('primary_color')->label('Primary token (--color-primary)'),
                Forms\Components\ColorPicker::make('secondary_color')->label('Secondary token (--color-secondary)'),
                Forms\Components\ColorPicker::make('accent_color')->label('Accent token (--color-accent)'),
            ])->columns(2),
            Section::make('Brand Engine')
                ->description('Upload logo/font/wallpaper, then generate a complete design system from your brand assets.')
                ->schema([
                    Forms\Components\FileUpload::make('font_path')
                        ->label('Brand font file')
                        ->disk('public')
                        ->directory('platform/fonts')
                        ->acceptedFileTypes(self::FONT_UPLOAD_MIME_TYPES)
                        ->preserveFilenames()
                        ->downloadable()
                        ->openable(),
                    Forms\Components\TextInput::make('font_source_url')
                        ->label('Google Fonts URL')
                        ->placeholder('https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap')
                        ->maxLength(2048)
                        ->url(),
                    Forms\Components\FileUpload::make('bg_image_path')
                        ->label('Wallpaper / background')
                        ->disk('public')
                        ->directory('platform/backgrounds')
                        ->image()
                        ->imageEditor()
                        ->preserveFilenames()
                        ->downloadable()
                        ->openable(),
                    Forms\Components\TextInput::make('font_heading')->label('Heading token (--font-heading)')->maxLength(120),
                    Forms\Components\TextInput::make('font_body')->label('Body token (--font-body)')->maxLength(120),
                    Forms\Components\ColorPicker::make('surface_color')->label('Surface token (--color-surface)'),
                    Actions::make([
                        Action::make('generateFromBrand')
                            ->label('Generate from brand')
                            ->icon('heroicon-m-sparkles')
                            ->action('generateFromBrand')
                            ->color('primary'),
                    ]),
                ])->columns(2),
            Section::make('Contact & support')->description('Shown in public pages, billing flows, emails, and footer areas.')->schema([
                Forms\Components\TextInput::make('support_email')->label('Support email')->email()->maxLength(120),
                Forms\Components\TextInput::make('billing_email')->label('Billing email')->email()->maxLength(120),
                Forms\Components\TextInput::make('contact_phone')->label('Contact phone')->tel()->maxLength(60),
                Forms\Components\Textarea::make('footer_text')->label('Footer text')->rows(3)->maxLength(500),
            ])->columns(2),
            Section::make('Public site')->description('Homepage copy, SEO metadata, registration gate, and optional announcement banner.')->schema([
                Forms\Components\TextInput::make('meta_title')->label('Meta title')->maxLength(120),
                Forms\Components\Textarea::make('meta_description')->label('Meta description')->rows(2)->maxLength(300),
                Forms\Components\TextInput::make('landing_headline')->label('Landing headline')->maxLength(160),
                Forms\Components\Textarea::make('landing_subheadline')->label('Landing subheadline')->rows(2)->maxLength(300),
                Forms\Components\TextInput::make('cta_label')->label('CTA label')->maxLength(80),
                Forms\Components\TextInput::make('cta_url')->label('CTA URL')->maxLength(255),
                Forms\Components\Toggle::make('enable_registration')->label('Enable public registration')->default(true),
                Forms\Components\Textarea::make('maintenance_message')->label('Maintenance / announcement banner')->rows(2)->maxLength(300),
            ])->columns(2),
            Section::make('Design token engine')->description('Theme overrides are stored in `titan_theme_tokens` as semantic tokens, and component tokens inherit from them automatically.')->schema([
                Forms\Components\Placeholder::make('token_exports')
                    ->hiddenLabel()
                    ->content('Use `php artisan titan:tokens:export` to export the current token set as CSS, Style Dictionary JSON, and Tailwind config.'),
            ]),
        ])->statePath('data');
    }

    public function generateFromBrand(BrandThemeGenerator $generator): void
    {
        $state = $this->form->getState();
        $disk = Storage::disk('public');

        $generated = $generator->generate([
            'logo_absolute_path' => $this->resolveStorageAbsolutePath($disk, $state['logo_path'] ?? null),
            'wallpaper_absolute_path' => $this->resolveStorageAbsolutePath($disk, $state['bg_image_path'] ?? null),
            'accent_color' => $state['accent_color'] ?? null,
            'font_source_url' => $state['font_source_url'] ?? null,
            'font_file_path' => $state['font_path'] ?? null,
        ]);

        $state['primary_color'] = $generated['primary_color'];
        $state['secondary_color'] = $generated['secondary_color'];
        $state['surface_color'] = $generated['surface_color'];
        $state['font_heading'] = $generated['font_heading'];
        $state['font_body'] = $generated['font_body'];
        $state['font_source_url'] = $generator->sanitizeGoogleFontsUrl($state['font_source_url'] ?? null);
        $this->form->fill($state);

        $orgName = $state['site_name'] ?: ($state['app_name'] ?? 'Org');
        $this->generatedThemeName = "Brand: {$orgName} — auto";
        $this->generatedThemePreview = [
            'primary_color' => $generated['primary_color'],
            'secondary_color' => $generated['secondary_color'],
            'surface_color' => $generated['surface_color'],
            'font_heading' => $generated['font_heading'],
            'font_body' => $generated['font_body'],
            'wcag_warning' => $generated['wcag_warning'],
            'contrast_ratio' => $generated['contrast_ratio'],
            'text_color' => $generated['text_color'],
            'name' => $this->generatedThemeName,
            'bg_image_url' => ! empty($state['bg_image_path']) ? $disk->url($state['bg_image_path']) : null,
        ];

        $settings = PlatformSetting::current();
        $snapshots = $settings->theme_snapshots ?? [];
        array_unshift($snapshots, [
            'name' => $this->generatedThemeName,
            'generated_at' => now()->toIso8601String(),
            'theme' => [
                'primary_color' => $state['primary_color'],
                'secondary_color' => $state['secondary_color'],
                'surface_color' => $state['surface_color'],
                'font_heading' => $state['font_heading'],
                'font_body' => $state['font_body'],
                'font_source_url' => $state['font_source_url'],
                'bg_image_path' => $state['bg_image_path'] ?? null,
            ],
            'wcag_warning' => $generated['wcag_warning'],
        ]);
        $settings->update(['theme_snapshots' => array_slice($snapshots, 0, self::MAX_THEME_SNAPSHOTS)]);
        cache()->forget('platform_settings');

        Notification::make()->title('Theme generated from brand assets')->success()->send();
    }

    private function resolveStorageAbsolutePath(FilesystemAdapter $disk, ?string $path): ?string
    {
        $normalizedPath = is_string($path) ? urldecode($path) : null;

        if (! is_string($normalizedPath) || $normalizedPath === '' || str_contains($normalizedPath, '..') || str_starts_with($normalizedPath, '/') || ! $disk->exists($normalizedPath)) {
            return null;
        }

        return $disk->path($normalizedPath);
    }

    public function save(): void
    {
        $state = $this->form->getState();
        $state['logo'] = $state['logo_path'] ?? null;
        $state['favicon'] = $state['favicon_path'] ?? null;
        $state['site_name'] = $state['site_name'] ?: ($state['app_name'] ?? 'TITAN ZERO');

        $generator = app(BrandThemeGenerator::class);
        $state['font_source_url'] = $generator->sanitizeGoogleFontsUrl($state['font_source_url'] ?? null);

        $settings = PlatformSetting::current();
        $settings->update($state);
        app(ThemeTokenManager::class)->savePlatformThemeTokens($settings->fresh(), $state);
        cache()->forget('platform_settings');

        Notification::make()->title('Site settings saved')->success()->send();
    }
}
