# Issue 198 — UI Studio Live Preview Sandbox

## Files Changed

| File | Summary |
|---|---|
| `app/Filament/Pages/UiStudio.php` | Added live preview state (`previewPanel`, `previewFrameSize`, `syncPreviewScroll`), panel option resolution from `config/titan_panels.php`, preview URL/CSS-variable payload helpers, and unsaved-theme snapshot tracking. |
| `resources/views/filament/pages/ui-studio.blade.php` | Reworked layout to a 40/60 controls/preview split, added sandboxed iframe preview toolbar (panel selector, Desktop/Tablet/Mobile toggle, sync-scroll toggle, unsaved indicator), switched component panel selector to config-driven options, and added postMessage-based iframe theme sync script. |
| `issue-docs/issue-198.md` | Implementation notes for this issue. |

## Fixes Applied

1. Implemented split-screen composition with controls on the left side and a dedicated live iframe preview on the right side.
2. Added sandboxed preview iframe that targets the active/selected Filament panel path and keeps navigation inside the preview frame.
3. Added postMessage-based CSS custom property updates to the iframe document so color/font token edits apply live without iframe reload.
4. Added responsive preview frame toggles (Desktop / Tablet / Mobile).
5. Added sync-scroll toggle to align controls scrolling with preview scrolling.
6. Added unsaved-theme indicator by diffing current theme state against the last published snapshot.
7. Replaced hardcoded panel options with canonical panel metadata from `config('titan_panels.panels')` (filtered by current user roles).

## Validation Notes

- `php -l app/Filament/Pages/UiStudio.php` passes.
- Full PHP test/build execution is blocked in this sandbox because the repository requires PHP 8.4 while the environment provides PHP 8.3.6.
- Frontend repo-wide checks have existing unrelated baseline failures (`npm run format:check`, `npm run lint`, and `npm run build` via Wayfinder/Artisan dependency path).

## Next Steps

1. Run full project validation in a PHP 8.4 environment (`composer run test`, app boot, and Vite build with Wayfinder generation).
2. Perform in-browser QA pass in a running Filament session for each panel option to confirm permissions and navigation expectations.
3. If needed, broaden theme var mapping to additional panel-specific CSS token names once visual QA identifies gaps.
