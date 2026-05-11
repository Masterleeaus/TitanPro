# Issue 197 — Theme Marketplace: Install, Export, Share & Import Theme Packs

## Issue Summary

Build a theme marketplace within the **UI Studio** where users can install pre-built theme
packs, export their own themes, and import themes shared by others.

---

## Files Changed

### New files

| File | Purpose |
|------|---------|
| `database/migrations/2026_05_11_000001_create_shared_themes_table.php` | Creates the `shared_themes` table that stores themes shared via public links (token, name, author, tokens JSON, views counter). |
| `app/Models/SharedTheme.php` | Eloquent model for `shared_themes`; `createFromTokens()` generates a random 24-char share token. |
| `app/Support/ThemePackManager.php` | Core service encapsulating: built-in theme catalogue (9 themes), ZIP validation, token sanitization, ZIP export, share-token creation, and share-token resolution. |
| `app/Console/Commands/ExportThemeCommand.php` | `titan:theme:export` artisan command — exports active or built-in theme as a redistributable ZIP. Supports `--builtin` flag and `--out=` path override. |
| `app/Http/Controllers/Platform/ThemeImportController.php` | `GET /theme/import/{token}` — resolves a share token and redirects to UI Studio with `?import_token=` query param. |
| `issue-docs/issue-197.md` | This file. |

### Modified files

| File | Changes |
|------|---------|
| `app/Filament/Pages/UiStudio.php` | Added `SharedTheme`, `ThemePackManager` imports; added marketplace state properties (`marketplaceTab`, `themeZipUpload`, `zipPreview`, `shareThemeName`, `generatedShareUrl`, `importUrl`, `importPreview`); added `applyBuiltinTheme()`, `previewZip()`, `installFromZip()`, `exportTheme()`, `shareTheme()`, `previewImport()`, `installFromUrl()` Livewire actions; `mount()` now handles `?import_token` query param to auto-open the Import sub-tab. |
| `resources/views/filament/pages/ui-studio.blade.php` | Tab strip updated to `overflow-x-auto` with `flex-none` tabs to accommodate 5th **Marketplace** tab; added full Marketplace panel with four sub-tabs: **Browse** (colour-swatch grid with star ratings and tags), **Install** (ZIP upload + validate + install flow), **Share** (share-link generator + ZIP export), **Import** (paste share URL → preview → install). |
| `routes/web.php` | Added `GET /theme/import/{token}` route (auth-guarded). |

---

## Features Implemented

| Requirement | Status | Notes |
|-------------|--------|-------|
| Theme pack format: `theme.json`, `preview.png`, `meta.json` in a ZIP | ✅ | `ThemePackManager::validateZip()` enforces this; `buildExportZip()` creates compliant ZIPs |
| Install tab: upload ZIP, validate, preview, apply | ✅ | `previewZip()` + `installFromZip()` in UiStudio; ZIP sub-tab in Marketplace |
| Browse tab: built-in curated themes with ratings & tags | ✅ | 9 themes: Ocean, Aurora, Nordic, Midnight Neon, Emerald Ops, Graphite Pro, Solarized, Teal Ops, Teal Dark |
| Export: download current theme as ZIP | ✅ | `exportTheme()` action streams a ZIP response via `response()->download()` |
| Share: generate shareable link stored in DB | ✅ | `shareTheme()` → `shared_themes` table → `/theme/import/{token}` |
| Import from URL: paste link, preview, install | ✅ | `previewImport()` + `installFromUrl()` with inline colour-swatch preview |
| Theme rating/tagging for built-in packs | ✅ | Static `rating` (float) and `tags` (string[]) per built-in theme; star rendering in Browse tab |
| `php artisan titan:theme:export {name}` CLI command | ✅ | `ExportThemeCommand` — auto-discovered from `app/Console/Commands/` |

---

## Architecture Notes

- **No new Filament pages** were added — all marketplace UI lives inside the existing right-panel
  of `UiStudio` as a `marketplace` tab, keeping the Studio experience unified.
- **Security**: all token values flowing through `ThemePackManager::sanitizeTokens()` are validated
  as hex colours (`#rgb` / `#rrggbb`) or safe font-name strings before being applied to
  `PlatformSetting` or `OrganizationBranding`, preventing CSS-injection.
- **Graceful degradation**: `shareTheme()` and `previewImport()` check for the `shared_themes`
  table via `Schema::hasTable()` and surface a notification if the migration hasn't run yet.
- **ZIP export** uses `ZipArchive` (bundled with PHP) and streams the file directly from a system
  temp path; no permanent disk writes are required.

---

## Next Steps

1. Run `php artisan migrate` to create the `shared_themes` table.
2. Run `php artisan titan:theme:export` to test the CLI command.
3. Optionally add per-org theme pack uploads to the `organization_brandings` table (extend with
   `installed_theme_packs` JSON column in a follow-up migration).
4. Add CRUD for user-uploaded custom themes stored in `theme_packs` table (future issue).
5. Rate-limiting / expiry for shared theme tokens (future issue).
