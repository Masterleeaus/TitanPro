# Issue 197 — ModuleManifestRegistryLoaderTest AI/Blueprint Destructuring Fix

## Issue Summary

`tests/Unit/Modules/ModuleManifestRegistryLoaderTest.php` had a `makeLoader()` destructuring site in the main idempotency test that did not include the `ai` and `blueprint` keys returned by `makeLoader()`. This left the AI registries unavailable in that test and prevented asserting AI/blueprint behavior against the shared loader registries.

## Files Changed

| File | Changes |
|------|---------|
| `tests/Unit/Modules/ModuleManifestRegistryLoaderTest.php` | Added missing `'ai' => $aiRegistry` and `'blueprint' => $blueprintAIRegistry` destructuring entries in the main idempotency test; added explicit AI and blueprint manifest fixtures for enabled/disabled modules; added assertions verifying AI and blueprint registry population/exclusion using the same registries returned by `makeLoader()`. |
| `issue-docs/issue-197.md` | Added issue implementation notes, changed files list, and next steps. |

## Fixes Applied

1. Updated the main `makeLoader()` destructuring block to include:
   - `ai` → `$aiRegistry`
   - `blueprint` → `$blueprintAIRegistry`
2. Added AI manifest and blueprint AI fixture data to the main idempotency test for `RegistryTestModule`.
3. Added disabled-module AI/blueprint fixture data and assertions to confirm disabled modules are excluded.
4. Added assertions in the main idempotency test that validate AI manifest loading and blueprint loading using loader-provided registries.

## Validation

- Attempted to run (before and after code changes): `./vendor/bin/pest tests/Unit/Modules/ModuleManifestRegistryLoaderTest.php`
- Result in this sandbox: `./vendor/bin/pest` is unavailable because `vendor/` is not installed.
- Dependency installation is blocked here because `composer install` fails on PHP 8.3.6 while `composer.json` requires PHP `^8.4`.

## Next Steps

1. Run `composer install` in a PHP 8.4+ environment.
2. Run `./vendor/bin/pest tests/Unit/Modules/ModuleManifestRegistryLoaderTest.php`.
3. If green, run the broader module unit test slice in CI to confirm no regressions.
