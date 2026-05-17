# Issue 422 — [FOLLOW-UP] Add per-org theme pack uploads

## Summary

Implemented per-organisation private theme pack libraries by extending
`organization_brandings` with `installed_theme_packs` and wiring the Theme
Marketplace install/browse flows to use it.

Source: Follow-up to `issue-docs/issue-197.md`.

---

## Files Changed

| File | Change type | Description |
|------|-------------|-------------|
| `database/migrations/2026_05_17_170500_add_installed_theme_packs_to_organization_brandings.php` | **new** | Adds nullable JSON `installed_theme_packs` column with default `[]` to `organization_brandings` (with guarded table/column checks). |
| `app/Models/OrganizationBranding.php` | **modified** | Added `installed_theme_packs` fillable/cast and helper methods: `installedThemePacks()`, `installThemePack()`, `uninstallThemePack()`. |
| `app/Filament/Pages/UiStudio.php` | **modified** | Added org-library helpers for marketplace (`installedMarketplaceThemes()`, `applyInstalledTheme()`) and persisted installed packs during ZIP/import installs via `installThemePackForOrganization()`. |
| `resources/views/filament/pages/ui-studio.blade.php` | **modified** | Updated Marketplace **Browse** sub-tab to display org-installed packs alongside curated built-in packs, including apply action for installed packs. |
| `tests/Feature/OrganizationBrandingTest.php` | **modified** | Added Pest coverage for install to JSON library, uninstall removal, and organization isolation behavior. |

---

## Fixes Applied

- Added tenant-scoped storage field for installed theme packs.
- Added model-level install/uninstall/list helpers for private org theme libraries.
- Persisted newly installed packs from marketplace install flows to org branding.
- Surfaced installed org packs in Marketplace Browse next to built-in themes.
- Added tests covering install, uninstall, and org isolation acceptance criteria.

---

## Validation

- `php -l` on modified PHP files: ✅
- `npm run test`: ✅ (98 passing)
- Backend Pest execution is currently blocked in this runner because `composer install`
  cannot complete on PHP 8.3.6 while `composer.lock` requires PHP 8.4 packages.

---

## Next Steps

- [ ] Run `composer install` and `./vendor/bin/pest tests/Feature/OrganizationBrandingTest.php` in a PHP 8.4 environment.
- [ ] Verify the Marketplace Browse/Install UI in a running Filament panel session and capture product QA screenshots.
