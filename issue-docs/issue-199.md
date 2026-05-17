# Issue 199 — UI Studio Visual Typography System

## Files changed
- `app/Filament/Pages/UiStudio.php`
- `resources/views/filament/pages/ui-studio.blade.php`
- `tests/Unit/UiStudioSortableAssetsTest.php`

## Fixes applied
- Added a dedicated **Typography** tab in UI Studio with:
  - preset family selector (Enterprise, Editorial, Compact, Code-forward, Luxury),
  - editable typography token controls (heading/body/code, scale, weights, line-height, letter-spacing),
  - type scale generation (`sm/base/lg/xl/2xl/3xl`),
  - live typography preview (heading, paragraph, caption, code sample).
- Added Google Fonts workflow with search filtering and apply-to-heading/body behavior, including stylesheet URL generation for runtime preview.
- Added custom **WOFF2 upload** handling to organisation storage (`organization-branding/{orgId}/fonts`) and preview runtime font-face injection.
- Persisted typography token values into the design token layer (`titan_theme_tokens`, `scope=typography`) during publish.
- Extended preview runtime CSS variable payload and iframe bridge to apply typography tokens and font sources live.
- Added focused unit assertions to verify typography system wiring in UI Studio source.

## Next steps
- Validate in a fully provisioned environment with PHP vendor dependencies installed (`vendor/bin/pest`, full build) to confirm runtime behavior end-to-end.
- Optionally extend tenant-scoped runtime token resolution if per-organisation typography token isolation is required across all panels.
