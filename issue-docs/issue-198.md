# Issue 198 — Tailwind v4 Configuration Alignment

## Files Changed

| File | Change |
|------|--------|
| `resources/css/app.css` | Migrated to Tailwind v4 CSS-first setup (`@import`, `@source`, `@theme`, `@plugin`, dark variant declaration). |
| `tailwind.config.js` | Removed legacy Tailwind v3-style JS config. |
| `package.json` | Removed duplicate `tailwindcss` v3 devDependency so Tailwind v4 is the active version. |

## Fixes Applied

1. Replaced legacy `@tailwind base/components/utilities` directives with Tailwind v4 `@import 'tailwindcss'`.
2. Moved content scanning paths from JS config into `@source` directives in `resources/css/app.css`.
3. Ported the custom sans font stack into a v4 `@theme` block.
4. Replaced JS plugin registration with CSS plugin directive `@plugin '@tailwindcss/forms'`.
5. Added a class-based dark mode variant declaration compatible with the project’s `.dark` strategy.
6. Removed `tailwind.config.js` to avoid v3-format config drift.

## Validation Notes

- Confirmed Tailwind compiles successfully with:
  - `npx @tailwindcss/cli -i ./resources/css/app.css -o /tmp/tw-after.css`
- Full Vite build in this sandbox remains blocked by missing/unsupported PHP runtime dependencies for `artisan wayfinder:generate`.

## Next Steps

1. Run `npm install` in CI or a PHP 8.4-compatible environment to refresh lock state with Tailwind v4 as the resolved version.
2. Run `npm run build` in CI after Composer dependencies are available to confirm no Tailwind warnings in the full Vite pipeline.
3. If additional legacy Tailwind JS config existed in module assets, migrate those to CSS-first v4 directives as a follow-up.
