## Issue #321 — [BUG] Filament Shield config sets auth_provider_model as array, breaks artisan commands

### Files Changed

| File | Change |
|------|--------|
| `issue-docs/issue-321.md` | Added issue resolution notes, validation attempts, and follow-ups. |

### Fix Applied

- Verified `config/filament-shield.php` already uses a single FQCN string for Shield auth provider:
  - `'auth_provider_model' => \App\Models\User::class,`
- No further code change was required in the config file for this issue.

### Validation Attempts

- `php artisan config:clear && php artisan about`  
  - Blocked in this sandbox before Laravel bootstrap because PHP dependencies are not installable on PHP 8.3.6 (`composer.json` requires PHP 8.4+ packages).
- `npm run build`  
  - Initially failed with `vite: not found` before Node dependencies were installed.
  - After `npm install`, build remains blocked by missing Laravel vendor bootstrap because Composer install is blocked by PHP version mismatch.
- `./vendor/bin/pest` (full suite)  
  - Blocked because `vendor/` is unavailable for the same Composer/PHP constraint reason.

### Follow-ups / Next Steps

1. Run validation on a PHP 8.4+ environment:
   - `composer install`
   - `php artisan config:clear && php artisan about`
   - `npm run build`
   - `./vendor/bin/pest`
2. Confirm all commands complete successfully now that Shield config uses string `auth_provider_model`.
