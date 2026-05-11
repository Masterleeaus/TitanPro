# Issue 347

## Summary

Primary scope: install the full TitanDocs module from `modulelib/sorted/TitanDocs_WIZARD_STANDARDS_HISTORY_v17/TitanDocs`, remove the live `Modules/Docs` stub manifest, and wire TitanDocs into the TitanPro panel with a lightweight Filament control page that links to the wizard, template library, and history screens. Secondary scope: patch vulnerable in-repo `axios` manifest declarations that were touched while integrating TitanDocs sources.

## Files Changed

| File | Change |
| --- | --- |
| `app/Http/Controllers/AccountBaseController.php` | Added a minimal account-area controller base required by imported TitanDocs controllers. |
| `Modules/Docs/module.json` | Removed the dead Docs stub manifest so it is no longer discovered as a module. |
| `Modules/TitanDocs/**` | Imported the TitanDocs module source from `modulelib/sorted/TitanDocs_WIZARD_STANDARDS_HISTORY_v17/TitanDocs`. |
| `Modules/TitanDocs/module.json` | Enabled the module and declared the TitanPro Filament plugin target. |
| `Modules/TitanDocs/Providers/TitanDocsServiceProvider.php` | Added config, translation, view, route, and migration bootstrapping for this host app. |
| `Modules/TitanDocs/Routes/web.php` | Restored legacy `/aidocument` endpoints and added canonical TitanDocs route aliases. |
| `Modules/TitanDocs/Http/Controllers/GeneratorWizardController.php` | Replaced missing helper usage with host-safe auth/user organization lookups. |
| `Modules/TitanDocs/Http/Controllers/AiTemplateController.php` | Patched permission checks, workspace handling, AI client usage, history filtering, and generation fallbacks. |
| `Modules/TitanDocs/Filament/Plugin/TitanDocsPlugin.php` | Registered TitanDocs into module-based Filament plugin injection. |
| `Modules/TitanDocs/Filament/Pages/TitanDocsControlPanel.php` | Added the TitanPro sidebar entry point under `Documents`. |
| `Modules/TitanDocs/Resources/views/filament/pages/control-panel.blade.php` | Added the TitanPro control page with wizard/library/history launch links. |
| `Modules/TitanDocs/Resources/views/document/history.blade.php` | Replaced legacy `Form` facade usage with plain HTML form posts. |
| `Modules/TitanDocs/Resources/views/document/*.blade.php` | Updated legacy AIDocument asset paths to TitanDocs paths. |
| `tests/Modules/TitanDocs/TitanDocsFeatureTest.php` | Added focused module tests for manifest replacement, panel exposure, wizard sessions, template selection, and AI generation persistence. |

## Fixes Applied

1. Replaced the inactive Docs module manifest with an active TitanDocs module.
2. Preserved the imported wizard/history/document flows while adapting them to this repository's auth, tenancy, and AI-client conventions.
3. Added a canonical Filament plugin/page so TitanDocs appears in the TitanPro sidebar under `Documents`.
4. Restored legacy `/aidocument/*` URLs expected by the imported Blade templates, while also providing `titan.docs.*` route names for new entry points.
5. Added graceful AI-generation fallback behavior so document generation persists output even when a TitanCore AI binding is not available.

## Validation

- `php -l app/Http/Controllers/AccountBaseController.php`
- `php -l Modules/TitanDocs/Providers/TitanDocsServiceProvider.php`
- `php -l Modules/TitanDocs/Routes/web.php`
- `php -l Modules/TitanDocs/Http/Controllers/GeneratorWizardController.php`
- `php -l Modules/TitanDocs/Http/Controllers/AiTemplateController.php`
- `php -l Modules/TitanDocs/Filament/Plugin/TitanDocsPlugin.php`
- `php -l Modules/TitanDocs/Filament/Pages/TitanDocsControlPanel.php`
- `php -l tests/Modules/TitanDocs/TitanDocsFeatureTest.php`

## Next Steps

1. Run `composer install` on a PHP 8.4-compatible runner so Laravel/vendor dependencies are available.
2. Run `php artisan module:migrate TitanDocs` and `./vendor/bin/pest tests/Modules/TitanDocs`.
3. Open the TitanPro panel and capture a live UI verification of the new TitanDocs sidebar/control page once the app dependencies are installed.
