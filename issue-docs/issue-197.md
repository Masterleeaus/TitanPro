# Issue 197 — [FOLLOW-UP] Add theme_packs table and CRUD for user-uploaded custom themes

## Files changed

| File | Action | Purpose |
|------|--------|---------|
| `database/migrations/2026_05_17_170900_create_theme_packs_table.php` | Added | Creates tenant-scoped `theme_packs` table with required columns (`organization_id`, `name`, `slug`, `description`, `tokens`, `preview_image_path`, `tags`, `is_public`, timestamps) plus indexes. |
| `app/Models/ThemePack.php` | Added | Adds tenant-aware `ThemePack` Eloquent model implementing `TenantAware` and `BelongsToTenant`. |
| `app/Filament/Pages/UiStudio.php` | Modified | Implements My Themes save/apply/edit/delete logic and loads saved packs into in-memory studio preview without publish. |
| `resources/views/filament/pages/ui-studio.blade.php` | Modified | Adds Marketplace **My Themes** sub-tab UI with list + save current + edit + delete + apply actions. |
| `tests/Feature/ThemePackMarketplaceTest.php` | Added | Covers create/list/apply/delete flows and cross-org isolation behavior. |

## Fixes applied

1. Added a new `theme_packs` migration with all acceptance-criteria fields and tenant uniqueness (`organization_id + slug`).
2. Added a `ThemePack` model that is tenant scoped by default and casts JSON token/tag fields.
3. Extended UI Studio Marketplace with a **My Themes** sub-tab:
   - Save current UI token state as a pack
   - Edit existing pack metadata
   - Delete pack
   - Apply pack for preview without committing
4. Added feature tests validating create, list, apply, delete, and cross-org tenant isolation.

## Validation notes

- Ran `npm ci` successfully.
- Ran `npm run test` successfully (frontend Vitest suite passed).
- `composer run test` could not run in this environment because `vendor/autoload.php` is missing (composer dependencies are not installed).
- `npm run lint` reports pre-existing unrelated ESLint errors in module build files using `require()`.

## Next steps

- Run PHP/Pest tests in a PHP 8.4 + composer-installed environment:
  - `./vendor/bin/pest tests/Feature/ThemePackMarketplaceTest.php`
- Optionally add UI-level Livewire browser checks for My Themes interactions once CI PHP runtime is aligned.
