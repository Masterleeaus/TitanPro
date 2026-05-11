# Issue 197 – Vue component test infrastructure for grid editor drag interactions

**Source:** Follow-up to issue-148.md (visual grid editor delivered in `Settings.vue` / `layoutTokens.ts`).

---

## Summary

Established Vitest + Vue Test Utils frontend testing infrastructure and wrote initial coverage for the grid editor's drag-driven flows that were explicitly flagged as a gap in issue-148.

---

## Changes Made

### New devDependencies (`package.json`)

| Package | Version | Purpose |
|---------|---------|---------|
| `vitest` | ^4.1.5 | Test runner (Vite-native, fast) |
| `@vue/test-utils` | ^2.4.10 | Vue 3 component mounting & assertions |
| `happy-dom` | ^20.9.0 | DOM simulation environment (patched; ≥ 20.8.9 fixes advisory CVEs) |
| `@vitest/coverage-v8` | ^4.1.5 | Optional coverage support (`npm run test:coverage`) |

### New scripts (`package.json`)

```json
"test":          "vitest run",
"test:watch":    "vitest",
"test:coverage": "vitest run --coverage"
```

### New file – `vitest.config.ts`

Minimal Vitest configuration:
- Uses `@vitejs/plugin-vue` (already in devDependencies) to transform `.vue` SFCs.
- Resolves the `@/` alias to `resources/js/` (matching `tsconfig.json`).
- Sets the test environment to `happy-dom`.
- Scans `resources/js/**/*.spec.ts` for test files.

### New file – `resources/js/pages/Platform/__tests__/layoutTokens.spec.ts`

33 pure-unit tests covering every exported function in `layoutTokens.ts`:

- `snapValue` – rounding to grid multiples, clamping, zero-delta identity.
- `cloneLayoutTokens` – shallow copy, mutation isolation.
- `sanitizeLayoutTokens` – defaults fill, all clamp ranges, `cardMinHeight` derivation.
- `extractLayoutTokens` – null/undefined/missing-block fallbacks, full parse, custom value, out-of-range sanitisation, user CSS outside the block.
- `buildLayoutTokenCss` – sentinel markers, all CSS custom properties, sanitisation, round-trip stability.
- `mergeLayoutTokenCss` – no-existing-CSS case, block replacement, user CSS preservation, no block duplication on repeated merges.
- `layoutPreviewStyles` – property strings, sanitisation.
- **Token round-trip** – extract → build → extract identity, modified tokens, multi-save stability.

### New file – `resources/js/pages/Platform/__tests__/Settings.spec.ts`

23 Vue component tests covering the grid editor's interactive behaviours.
Dependencies (`@inertiajs/vue3`, `PlatformLayout`) are stubbed at the module level so the component mounts in isolation.

| Group | Tests |
|-------|-------|
| **Sidebar width drag** | increases on rightward drag, decreases on leftward drag, clamps to 192px min, clamps to 384px max, history pushed on `pointerup` |
| **Content width drag** | increases on rightward drag, clamps to 720px min, clamps to 1440px max |
| **Snap-to-grid** | 8px snap (default), 4px snap after `<select>` change, row-gap snapped on resize-rows drag |
| **Undo / redo** | undo restores previous value, redo re-applies, Undo disabled at initial state, Redo disabled at initial state, Redo disabled after new drag (future history pruned), `Ctrl+Z` keyboard undo, `Ctrl+Y` keyboard redo |
| **Reset** | restores all tokens to defaults, pushes undo-able history entry, works when `custom_css` had persisted custom values |
| **Token initialisation** | reads persisted tokens from `custom_css` on mount, falls back to defaults when `custom_css` is `null` |

### Updated file – `.github/workflows/production-check.yml`

Added a new `frontend-tests` job that:
1. Checks out code.
2. Installs Node 22.
3. Runs `npm install`.
4. Runs `npm run test`.

This job runs in parallel with (not gating) the existing `production-check` job.

---

## Next Steps

- Consider adding coverage reporting (`npm run test:coverage`) and uploading the report as a CI artefact.
- Card-span drag tests (`primary-card` / `secondary-card`) currently rely on the JSDOM `clientWidth = 0` fallback path; if realistic span arithmetic is required, `Object.defineProperty` on the `previewGrid` element can inject a mock pixel width.
- Integrate `npm run lint` into the CI workflow once the pre-existing 4 000+ lint errors in the repository are resolved (they pre-date this issue and are unrelated to these changes).
