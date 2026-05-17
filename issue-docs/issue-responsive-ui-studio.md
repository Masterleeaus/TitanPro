# Issue: Responsive UI Studio Preview + Breakpoint Token Overrides

## Files changed

- `app/Filament/Pages/UiStudio.php`
- `app/Support/ThemeTokenManager.php`
- `resources/views/filament/pages/ui-studio.blade.php`
- `issue-docs/issue-responsive-ui-studio.md`
- `scripts/install-responsive-ui-studio.sh`

## Fixes applied

- Added Live Preview device modes for desktop, tablet, mobile, collapsed sidebar, and customer portal simulation.
- Added iframe width switching using fixed viewport widths: 1440px, 1024px, and 390px.
- Added per-breakpoint responsive design token editing for sidebar width, layout gaps, card padding, heading sizes, body font size, and table cell padding.
- Added responsive token persistence using separate `titan_theme_tokens` scopes: `responsive:desktop`, `responsive:tablet`, and `responsive:mobile`.
- Added customer preview routing to the ZeroFuss/customer portal path.
- Added collapsed sidebar preview mode with injected preview-only CSS.
- Added configurable mobile table column visibility controls and persisted them separately under `responsive:tables`.
- Added preview iframe bridge CSS to reduce mobile overflow and apply mobile typography/card/table adjustments.
- Updated preview panel options so super admins can preview all registered panels.

## Responsive token storage approach

Base theme tokens remain in their existing semantic/component scopes. Responsive overrides are stored separately in `titan_theme_tokens` using breakpoint-specific scopes:

- `responsive:desktop`
- `responsive:tablet`
- `responsive:mobile`
- `responsive:tables`

This keeps base theme tokens untouched while allowing breakpoint-specific overrides.

## Preview modes implemented

| Mode | Width | Notes |
|------|-------|-------|
| Desktop | 1440px | Full admin dashboard preview |
| Tablet | 1024px | Tablet layout preview |
| Mobile | 390px | Mobile no-horizontal-scroll target |
| Collapsed sidebar | 1440px | Icon-only sidebar simulation |
| Customer portal | 390px | ZeroFuss/customer-facing simulation |

## Test results

Static checks completed locally:

```bash
php -l app/Filament/Pages/UiStudio.php
php -l app/Support/ThemeTokenManager.php
```

Both passed.

## Next steps

After uploading the delta, run:

```bash
cd /home/saassmar/domains/tradiesm.art/public_html
bash scripts/install-responsive-ui-studio.sh
```

Then manually verify:

- `/platform` → UI Studio page loads.
- Live Preview toolbar shows all five modes.
- iframe width changes per mode.
- mobile preview is 390px and avoids horizontal scroll.
- collapsed sidebar mode hides sidebar text labels.
- customer portal mode previews the customer-facing panel.
- responsive overrides save separately from base theme tokens.
