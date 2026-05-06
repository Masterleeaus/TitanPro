# TitanPro — Theme System

TitanPro uses **[alizharb/filament-themes-manager](https://github.com/alizharb/filament-themes-manager)** for theme management within the Filament v4 admin panel, plus a custom `ThemeManager` page (at `app/Filament/Pages/ThemeManager.php`) that fixes an upload-path resolution bug in v1.x of the package.

---

## Directory Structure

```
themes/
├── README.md             ← This file
├── .gitkeep              ← Keeps the directory in git
└── {theme-slug}/         ← Installed theme directories
    ├── theme.json        ← Theme manifest (required)
    ├── css/              ← Theme CSS assets
    ├── js/               ← Theme JS assets (optional)
    └── ...
```

---

## Compatible Filament v4 Themes

The following themes have been evaluated and are compatible with Filament v4 / PHP 8.4:

### 1. openplain/filament-shadcn-theme *(already installed)*
- **Package:** `openplain/filament-shadcn-theme`
- **Composer:** `composer require openplain/filament-shadcn-theme`
- **License:** MIT
- **Notes:** shadcn/ui-inspired design system for Filament v4. Clean, minimal aesthetic.
- **Status:** ✅ Installed via composer.json

### 2. osamanagi/filament-abyss-theme *(already installed)*
- **Package:** `osamanagi/filament-abyss-theme`
- **Composer:** `composer require osamanagi/filament-abyss-theme`
- **License:** MIT
- **Notes:** Dark-first premium theme with deep navy/slate color palette.
- **Status:** ✅ Installed via composer.json

### 3. hasnaaadh/filament-tailark-theme
- **Repo:** https://github.com/hasnaaadh/filament-tailark-theme
- **License:** MIT
- **Notes:** Tailwind-native theme, clean admin dashboard aesthetic.
- **Status:** Available for install

### 4. guava/filament-cupertino-theme
- **Repo:** https://github.com/GuavaCZ/filament-cupertino-theme  
- **License:** MIT
- **Notes:** Apple/iOS-inspired UI theme for Filament v3/v4.
- **Status:** Check v4 compatibility before installing

---

## Installing a Theme from ZIP

1. Go to **Admin → Theme Manager** (`/admin/theme-manager`)
2. Select **Upload ZIP** tab
3. Upload a `.zip` containing:
   - `theme.json` manifest at the root
   - `css/theme.css` stylesheet
4. Click **Install**

The `theme.json` manifest format:
```json
{
    "name": "My Custom Theme",
    "slug": "my-custom-theme",
    "version": "1.0.0",
    "author": "Your Name",
    "description": "A custom Filament theme",
    "filament_version": "^4.0"
}
```

### ZIP Structure Example
```
my-custom-theme.zip
├── theme.json
├── css/
│   └── theme.css
└── js/
    └── theme.js    (optional)
```

---

## Theme CSS Compilation (Vite)

The active Filament admin theme CSS is compiled via Vite. The entry point is:

```
resources/css/filament/admin/theme.css
```

This file is registered in `vite.config.ts`:
```typescript
laravel({
    input: [
        // ...
        'resources/css/filament/admin/theme.css',
    ],
})
```

And referenced in `AdminPanelProvider.php`:
```php
->viteTheme('resources/css/filament/admin/theme.css')
```

To apply a custom theme's CSS:
1. Copy or import the theme CSS into `resources/css/filament/admin/theme.css`
2. Run `npm run build` to recompile
3. The compiled CSS is served via Vite asset hashing

---

## Activating a Theme

After installation, activate a theme via the Theme Manager or programmatically:

```php
// In config/theme.php
'active' => 'my-custom-theme-slug',
```

Or via artisan (if the package provides a command):
```bash
php artisan theme:activate my-custom-theme
```

---

## Dark Mode

All built-in admin widget CSS uses Tailwind's `dark:` prefix classes and is dark-mode compatible. The Filament admin panel supports dark mode natively via the panel's `darkMode` setting.

---

## Troubleshooting

| Issue | Solution |
|-------|----------|
| ZIP upload silently fails | Ensure `config/filament-themes-manager.php` has correct `upload_disk` (default: `public`) |
| Theme CSS not loading | Run `npm run build` after installing a new theme |
| Missing `theme.json` in ZIP | Add a valid manifest file — see format above |
| Incompatible Filament version | Check the theme's `filament_version` constraint |
