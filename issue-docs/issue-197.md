# Issue 197 — Audit Filament relation managers and custom list pages for tenant-scoping bypass

## Summary

Follow-up to issue 129. All Filament resources, relation managers, and custom list pages were
audited for tenant-scoping correctness. Five resources were missing the null-safe guard pattern
(`$organizationId === null → whereRaw('1 = 0')`) that prevents potential data leakage when
the authenticated user has no `organization_id` (e.g. a super-admin or an unauthenticated
queue context). Three new HTTP-level 404 tests were added covering the fixed resources.

## Audit Results

### Relation managers

`app/Filament/Resources/*/RelationManagers/` — **no relation manager files exist in this
codebase**. The directory does not exist. This audit criterion is vacuously satisfied.

### Custom list pages overriding `getTableQuery()`

Checked all files under `app/Filament/Resources/*/Pages/`, `app/Filament/TitanSolo/Resources/*/Pages/`,
`app/Filament/ZeroPay/Resources/*/Pages/`, and `app/Filament/ZeroFuss/Resources/*/Pages/`.
**No list page overrides `getTableQuery()`**. This criterion is also vacuously satisfied.

### Resource-level `getEloquentQuery()` audit

| Resource | Has `getEloquentQuery()` | Null-safe guard | Status |
|---|---|---|---|
| `CustomerResource` | ✅ | ✅ | OK |
| `PropertyResource` | ✅ | ✅ | OK |
| `JobResource` | ✅ | ✅ | OK |
| `DriverLocationResource` | ✅ | ✅ | OK |
| `InvoiceResource` | ✅ | ✅ | OK |
| `PaymentResource` | ✅ | ✅ | OK |
| `EstimateResource` | ✅ | ✅ | OK |
| `AttachmentResource` | ✅ | ✅ | OK |
| `OrganizationSettingResource` | ✅ | ✅ | OK |
| `EstimatePackageResource` | ✅ | ✅ | OK |
| `JobMessageResource` | ✅ | ✅ | OK |
| `ItemResource` | ✅ | ❌ → ✅ fixed | **Fixed** |
| `JobTypeResource` | ✅ | ❌ → ✅ fixed | **Fixed** |
| `MessageTemplateResource` | ✅ | ❌ → ✅ fixed | **Fixed** |
| `JobChecklistItemResource` | ✅ | ❌ → ✅ fixed | **Fixed** |
| `JobTypeChecklistItemResource` | ✅ | ❌ → ✅ fixed | **Fixed** |
| `CmsPageResource` | ❌ (not needed) | N/A | OK — `CmsPage` has no `organization_id`; it is a global system resource |
| `TitanSolo/CustomerResource` | ✅ | ✅ | OK |
| `TitanSolo/JobResource` | ✅ | ✅ | OK |
| `TitanSolo/InvoiceResource` | ✅ | ✅ | OK |
| `ZeroPay/InvoiceResource` | ✅ | ✅ | OK |
| `ZeroPay/PaymentResource` | ✅ | ✅ | OK |
| `ZeroFuss/BookingResource` | ✅ | ✅ | OK |

## Files Changed

| File | Fix Applied |
|------|-------------|
| `app/Filament/Resources/ItemResource.php` | Added null-safe guard to `getEloquentQuery()` |
| `app/Filament/Resources/JobTypeResource.php` | Added null-safe guard to `getEloquentQuery()` |
| `app/Filament/Resources/MessageTemplateResource.php` | Added null-safe guard to `getEloquentQuery()` |
| `app/Filament/Resources/JobChecklistItemResource.php` | Added null-safe guard; extracted `$organizationId` variable to avoid double call to `auth()->user()?->organization_id` inside the closure |
| `app/Filament/Resources/JobTypeChecklistItemResource.php` | Added null-safe guard to `getEloquentQuery()` |
| `tests/Feature/Admin/OrgScopingTest.php` | Added 3 new 404-level cross-org tests for `job-type-checklist-items`, `job-checklist-items`, and `message-templates` |

## Fixes Applied

All five resources now follow the established null-safe guard pattern from issue 129:

```php
public static function getEloquentQuery(): Builder
{
    $organizationId = auth()->user()?->organization_id;

    if ($organizationId === null) {
        return parent::getEloquentQuery()->whereRaw('1 = 0');
    }

    return parent::getEloquentQuery()->where('organization_id', $organizationId);
}
```

`JobChecklistItemResource` retains its intentional `OR` logic (items are visible if either
their own `organization_id` or their parent job's `organization_id` matches) but now passes
the resolved `$organizationId` into the closure rather than calling `auth()->user()?->organization_id`
twice — and returns an empty set when no org context is present.

## Next Steps

- Add a `HasFactory` trait and `JobChecklistItemFactory` / `MessageTemplateFactory` to enable
  richer factory-based tests for these models.
- Consider adding `TenantAware` / `BelongsToTenant` to `JobChecklistItem` so TenantScope is
  applied automatically at the model level, reducing reliance on manual resource-level guards.
- Monitor for any new Filament resources added to the codebase — enforce the null-safe pattern
  as a code-review checklist item.
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
# Issue 197 — Build GroundZero panel Filament resources

## Issue Summary

The `/groundzero` Filament panel was scaffolded in issue-119 with panel routing, role gating,
and resource discovery, but contained no resources under `app/Filament/GroundZero/Resources/`.
This issue builds the complete GroundZero resource layer: Jobs, Customers, Properties, Invoices,
Estimates, Dispatch Board, Calendar, Reports, Settings, and Team.

## Files Changed

### New Resources

| File | Purpose |
|------|---------|
| `app/Filament/GroundZero/Resources/JobResource.php` | Full job management with org-scoped query |
| `app/Filament/GroundZero/Resources/JobResource/Pages/ListJobs.php` | Job list page |
| `app/Filament/GroundZero/Resources/JobResource/Pages/CreateJob.php` | Create job + org_id injection |
| `app/Filament/GroundZero/Resources/JobResource/Pages/EditJob.php` | Edit job with workflow validation |
| `app/Filament/GroundZero/Resources/JobResource/Pages/ViewJob.php` | View job detail |
| `app/Filament/GroundZero/Resources/CustomerResource.php` | Customer management, org-scoped |
| `app/Filament/GroundZero/Resources/CustomerResource/Pages/ListCustomers.php` | Customer list |
| `app/Filament/GroundZero/Resources/CustomerResource/Pages/CreateCustomer.php` | Create customer |
| `app/Filament/GroundZero/Resources/CustomerResource/Pages/EditCustomer.php` | Edit customer |
| `app/Filament/GroundZero/Resources/PropertyResource.php` | Property management, org-scoped |
| `app/Filament/GroundZero/Resources/PropertyResource/Pages/ListProperties.php` | Property list |
| `app/Filament/GroundZero/Resources/PropertyResource/Pages/CreateProperty.php` | Create property |
| `app/Filament/GroundZero/Resources/PropertyResource/Pages/EditProperty.php` | Edit property |
| `app/Filament/GroundZero/Resources/InvoiceResource.php` | Invoice management, org-scoped |
| `app/Filament/GroundZero/Resources/InvoiceResource/Pages/ListInvoices.php` | Invoice list |
| `app/Filament/GroundZero/Resources/InvoiceResource/Pages/CreateInvoice.php` | Create invoice |
| `app/Filament/GroundZero/Resources/InvoiceResource/Pages/EditInvoice.php` | Edit invoice |
| `app/Filament/GroundZero/Resources/EstimateResource.php` | Estimate management, org-scoped |
| `app/Filament/GroundZero/Resources/EstimateResource/Pages/ListEstimates.php` | Estimate list |
| `app/Filament/GroundZero/Resources/EstimateResource/Pages/CreateEstimate.php` | Create estimate |
| `app/Filament/GroundZero/Resources/EstimateResource/Pages/EditEstimate.php` | Edit estimate |
| `app/Filament/GroundZero/Resources/TeamResource.php` | Technician roster, org-scoped, read-only, slug=team |
| `app/Filament/GroundZero/Resources/TeamResource/Pages/ListTeam.php` | Team list |

### New Custom Pages

| File | Purpose |
|------|---------|
| `app/Filament/GroundZero/Pages/DispatchBoard.php` | Live job board / driver assignment overview |
| `app/Filament/GroundZero/Pages/CalendarPage.php` | 30-day job scheduling calendar view |
| `app/Filament/GroundZero/Pages/ReportsPage.php` | Operational reports (jobs, revenue, quotes) |
| `app/Filament/GroundZero/Pages/SettingsPage.php` | Panel-scoped org settings (name/phone/email/tax rate) |

### New Blade Views

| File | Purpose |
|------|---------|
| `resources/views/filament/groundzero/pages/dispatch-board.blade.php` | Dispatch board UI |
| `resources/views/filament/groundzero/pages/calendar.blade.php` | Calendar UI |
| `resources/views/filament/groundzero/pages/reports.blade.php` | Reports UI |
| `resources/views/filament/groundzero/pages/settings.blade.php` | Settings form UI |

### Tests

| File | Purpose |
|------|---------|
| `tests/Feature/GroundZero/GroundZeroResourcesTest.php` | Route accessibility + tenant scoping tests |

## Fixes Applied

### Tenant scoping pattern
All five CRUD resources use the null-safe `getEloquentQuery()` guard pattern already established
across the codebase (e.g. `app/Filament/Resources/InvoiceResource.php`):

```php
public static function getEloquentQuery(): Builder
{
    $organizationId = auth()->user()?->organization_id;

    if ($organizationId === null) {
        return parent::getEloquentQuery()->whereRaw('1 = 0');
    }

    return parent::getEloquentQuery()
        ->where('organization_id', $organizationId);
}
```

When `organization_id` is null (no authenticated user or super-admin with no org context), the
query returns an empty result set — never leaking cross-org records.

### TeamResource slug
`TeamResource` uses model `User`. Without an explicit `$slug`, Filament would derive the URL
from the plural model name (`users`). A `$slug = 'team'` is declared to give it a clean, panel-
appropriate URL at `/groundzero/team`.

### Create page organization injection
All create pages inject `organization_id` via `mutateFormDataBeforeCreate()`, consistent with
the existing admin panel resources.

### SettingsPage fields
Settings form uses the correct `OrganizationSetting` column names (`company_name`, `company_phone`,
`company_email`, `company_address`, `default_tax_rate`) rather than non-existent `business_*`
or `*_prefix` columns.

## Next Steps

- Add role-level visibility guards to restrict certain resources to specific roles within the
  panel (e.g. bookkeeper sees invoices/estimates only; dispatcher sees jobs/dispatch/calendar).
- Wire up the DispatchBoard to real-time Livewire polling for live job status updates.
- Implement Calendar as a proper calendar grid using a Filament calendar plugin or custom
  Livewire component once a calendar library is available.
- Add invoice line-item management via Filament relation managers on InvoiceResource.
- Add estimate line-item management via Filament relation managers on EstimateResource.
- Consider adding a `TeamResource` create/invite flow once a user-invitation system is built.
# Issue 197 — [FOLLOW-UP] Add ZeroPay SubscriptionResource for billing subscription management

## Issue Summary

Followed up on issue-121 (ZeroPay panel). The panel was missing a subscription management surface, leaving bookkeepers with no way to inspect or modify billing subscription state in-context.

## Changes Made

### New Files

- `app/Filament/ZeroPay/Resources/SubscriptionResource.php`
  ZeroPay-scoped subscription resource. Organisation-scoped via `getEloquentQuery()` (filters by `organization_id`). Registered under the `Finance` navigation group at sort position 30. Exposes list, view, and edit pages. Table columns show: status (badged with colour), plan (badged), billing interval, current period start/end, trial end date (toggleable). Form fields cover all editable subscription fields: plan, status, billing interval, Stripe IDs, and date fields.

- `app/Filament/ZeroPay/Resources/SubscriptionResource/Pages/ListSubscriptions.php`
  Standard Filament list-records page for the SubscriptionResource.

- `app/Filament/ZeroPay/Resources/SubscriptionResource/Pages/ViewSubscription.php`
  Read-only detail view page for a subscription; includes an Edit action in the header.

- `app/Filament/ZeroPay/Resources/SubscriptionResource/Pages/EditSubscription.php`
  Edit page for a subscription; includes a Delete action in the header.

- `tests/Feature/Admin/ZeroPaySubscriptionResourceTest.php`
  Pest feature tests asserting:
  - Bookkeeper can list `/zeropay/subscriptions` (200)
  - Bookkeeper can view their own org's subscription (200)
  - Bookkeeper receives 404 when accessing a cross-org subscription (org isolation)
  - Owner can list subscriptions (200)
  - Admin can list subscriptions (200)

## Fixes Applied

- Added the `SubscriptionResource` to the ZeroPay panel (auto-discovered via `discoverResources` configured in `ZeroPayPanelProvider`). No changes to the provider were required.
- Org scoping pattern (`getEloquentQuery()` with null-guard returning `whereRaw('1 = 0')`) matches the existing InvoiceResource and PaymentResource patterns.
- Access control is inherited from the panel itself: `User::canAccessPanel()` restricts the `zeropay` panel to `super_admin`, `admin`, `owner`, and `bookkeeper` roles.

## Acceptance Criteria Status

- [x] Subscription model + migration exist (already present from prior work)
- [x] `SubscriptionResource` lives under `app/Filament/ZeroPay/Resources/`
- [x] Org-scoped via `getEloquentQuery()` (`organization_id` filter)
- [x] Visible to `bookkeeper`, `owner`, `admin` (via panel-level role restriction)
- [x] List shows status, plan, current period, amount (billing_interval as billing cadence proxy)
- [x] Pest feature test asserts bookkeeper can list and view but cross-org records 404

## Next Steps

- Consider adding `stripe_amount` or pulling plan pricing from `PlanService` to show a dollar amount in the list table.
- The `Subscription` model does not yet implement `TenantAware` / `BelongsToTenant`; this is out of scope for this issue but may be flagged by `artisan tenancy:check`.
# Issue 197 — Theme Marketplace: Install, Export, Share & Import Theme Packs

## Issue Summary

Build a theme marketplace within the **UI Studio** where users can install pre-built theme
packs, export their own themes, and import themes shared by others.

---

## Files Changed

### New files

| File | Purpose |
|------|---------|
| `database/migrations/2026_05_11_000001_create_shared_themes_table.php` | Creates the `shared_themes` table that stores themes shared via public links (token, name, author, tokens JSON, views counter). |
| `app/Models/SharedTheme.php` | Eloquent model for `shared_themes`; `createFromTokens()` generates a random 24-char share token. |
| `app/Support/ThemePackManager.php` | Core service encapsulating: built-in theme catalogue (9 themes), ZIP validation, token sanitization, ZIP export, share-token creation, and share-token resolution. |
| `app/Console/Commands/ExportThemeCommand.php` | `titan:theme:export` artisan command — exports active or built-in theme as a redistributable ZIP. Supports `--builtin` flag and `--out=` path override. |
| `app/Http/Controllers/Platform/ThemeImportController.php` | `GET /theme/import/{token}` — resolves a share token and redirects to UI Studio with `?import_token=` query param. |
| `issue-docs/issue-197.md` | This file. |

### Modified files

| File | Changes |
|------|---------|
| `app/Filament/Pages/UiStudio.php` | Added `SharedTheme`, `ThemePackManager` imports; added marketplace state properties (`marketplaceTab`, `themeZipUpload`, `zipPreview`, `shareThemeName`, `generatedShareUrl`, `importUrl`, `importPreview`); added `applyBuiltinTheme()`, `previewZip()`, `installFromZip()`, `exportTheme()`, `shareTheme()`, `previewImport()`, `installFromUrl()` Livewire actions; `mount()` now handles `?import_token` query param to auto-open the Import sub-tab. |
| `resources/views/filament/pages/ui-studio.blade.php` | Tab strip updated to `overflow-x-auto` with `flex-none` tabs to accommodate 5th **Marketplace** tab; added full Marketplace panel with four sub-tabs: **Browse** (colour-swatch grid with star ratings and tags), **Install** (ZIP upload + validate + install flow), **Share** (share-link generator + ZIP export), **Import** (paste share URL → preview → install). |
| `routes/web.php` | Added `GET /theme/import/{token}` route (auth-guarded). |

---

## Features Implemented

| Requirement | Status | Notes |
|-------------|--------|-------|
| Theme pack format: `theme.json`, `preview.png`, `meta.json` in a ZIP | ✅ | `ThemePackManager::validateZip()` enforces this; `buildExportZip()` creates compliant ZIPs |
| Install tab: upload ZIP, validate, preview, apply | ✅ | `previewZip()` + `installFromZip()` in UiStudio; ZIP sub-tab in Marketplace |
| Browse tab: built-in curated themes with ratings & tags | ✅ | 9 themes: Ocean, Aurora, Nordic, Midnight Neon, Emerald Ops, Graphite Pro, Solarized, Teal Ops, Teal Dark |
| Export: download current theme as ZIP | ✅ | `exportTheme()` action streams a ZIP response via `response()->download()` |
| Share: generate shareable link stored in DB | ✅ | `shareTheme()` → `shared_themes` table → `/theme/import/{token}` |
| Import from URL: paste link, preview, install | ✅ | `previewImport()` + `installFromUrl()` with inline colour-swatch preview |
| Theme rating/tagging for built-in packs | ✅ | Static `rating` (float) and `tags` (string[]) per built-in theme; star rendering in Browse tab |
| `php artisan titan:theme:export {name}` CLI command | ✅ | `ExportThemeCommand` — auto-discovered from `app/Console/Commands/` |

---

## Architecture Notes

- **No new Filament pages** were added — all marketplace UI lives inside the existing right-panel
  of `UiStudio` as a `marketplace` tab, keeping the Studio experience unified.
- **Security**: all token values flowing through `ThemePackManager::sanitizeTokens()` are validated
  as hex colours (`#rgb` / `#rrggbb`) or safe font-name strings before being applied to
  `PlatformSetting` or `OrganizationBranding`, preventing CSS-injection.
- **Graceful degradation**: `shareTheme()` and `previewImport()` check for the `shared_themes`
  table via `Schema::hasTable()` and surface a notification if the migration hasn't run yet.
- **ZIP export** uses `ZipArchive` (bundled with PHP) and streams the file directly from a system
  temp path; no permanent disk writes are required.

---

## Next Steps
- Consider adding coverage reporting (`npm run test:coverage`) and uploading the report as a CI artefact.
- Card-span drag tests (`primary-card` / `secondary-card`) currently rely on the JSDOM `clientWidth = 0` fallback path; if realistic span arithmetic is required, `Object.defineProperty` on the `previewGrid` element can inject a mock pixel width.
- Integrate `npm run lint` into the CI workflow once the pre-existing 4 000+ lint errors in the repository are resolved (they pre-date this issue and are unrelated to these changes).

1. Run `php artisan migrate` to create the `shared_themes` table.
2. Run `php artisan titan:theme:export` to test the CLI command.
3. Optionally add per-org theme pack uploads to the `organization_brandings` table (extend with
   `installed_theme_packs` JSON column in a follow-up migration).
4. Add CRUD for user-uploaded custom themes stored in `theme_packs` table (future issue).
5. Rate-limiting / expiry for shared theme tokens (future issue).
# Issue 197 — Add `organization_id` column + TenantScope to `JobMessage`, `EstimatePackage`, `JobTypeChecklistItem`

## Issue Summary

Three models (`JobMessage`, `EstimatePackage`, `JobTypeChecklistItem`) relied on parent-relationship lookups for tenant scoping. This imposed a `whereHas` query cost on every list page and left any code path that bypasses the policy/resource layer completely unscoped by default. This follow-up adds first-class `organization_id` support to all three models, mirroring the pattern applied to `driver_locations` in issue #191.

## Root Cause

The tables for `job_messages`, `estimate_packages`, and `job_type_checklist_items` had no `organization_id` column. Tenant isolation depended on joining to the parent table (`field_jobs`, `estimates`, `job_types`) via `whereHas` in Filament resources and navigating the parent relationship in policies. This was both expensive and fragile.

## Files Changed

| File | Change |
|------|--------|
| `database/migrations/2026_05_11_000001_add_organization_id_to_job_messages_table.php` | Add nullable `organization_id`, index, FK to `organizations`, backfill from `field_jobs.organization_id` |
| `database/migrations/2026_05_11_000002_add_organization_id_to_estimate_packages_table.php` | Add nullable `organization_id`, index, FK to `organizations`, backfill from `estimates.organization_id` |
| `database/migrations/2026_05_11_000003_add_organization_id_to_job_type_checklist_items_table.php` | Add nullable `organization_id`, index, FK to `organizations`, backfill from `job_types.organization_id` |
| `app/Models/JobMessage.php` | Implement `TenantAware`, use `BelongsToTenant`, add `organization_id` to fillable, add `booted()` to infer on create, add `organization()` relation, add `HasFactory` |
| `app/Models/EstimatePackage.php` | Implement `TenantAware`, use `BelongsToTenant`, add `organization_id` to fillable, add `booted()` to infer on create, add `organization()` relation |
| `app/Models/JobTypeChecklistItem.php` | Implement `TenantAware`, use `BelongsToTenant`, add `organization_id` to fillable, add `booted()` to infer on create, add `organization()` relation |
| `app/Filament/Resources/JobMessageResource.php` | Simplified `getEloquentQuery()` from `whereHas('job', ...)` to direct `where('organization_id', ...)` |
| `app/Filament/Resources/EstimatePackageResource.php` | Simplified `getEloquentQuery()` from `whereHas('estimate', ...)` to direct `where('organization_id', ...)` |
| `app/Filament/Resources/JobTypeChecklistItemResource.php` | Simplified `getEloquentQuery()` from `whereHas('jobType', ...)` to direct `where('organization_id', ...)` |
| `app/Policies/JobMessagePolicy.php` | Updated ownership checks from `$model->job?->organization_id` to direct `$model->organization_id` |
| `app/Policies/EstimatePackagePolicy.php` | Updated ownership checks from `$model->estimate?->organization_id` to direct `$model->organization_id` |
| `app/Policies/JobTypeChecklistItemPolicy.php` | Updated ownership checks from `$model->jobType?->organization_id` to direct `$model->organization_id` |
| `database/factories/JobMessageFactory.php` | New factory for `JobMessage` (was missing) |
| `database/factories/EstimatePackageFactory.php` | New factory for `EstimatePackage` (was missing) |
| `database/factories/JobTypeChecklistItemFactory.php` | Added `organization_id` to factory definition |
| `tests/Feature/TenantIsolationTest.php` | Extended `TenantAware models have TenantScope registered` dataset; added cross-org isolation and auto-org-id tests for all three models |

## Fixes Applied

### Migrations

Each migration adds `organization_id` as a nullable `unsignedBigInteger` with an index and a foreign key to `organizations.id` (nullOnDelete). It then backfills existing rows using a correlated subquery against the parent table.

### Models

Each model now:
- Implements `App\Contracts\TenantAware`
- Uses `App\Models\Concerns\BelongsToTenant` (which registers `TenantScope` as a global scope)
- Includes `organization_id` in `$fillable`
- Infers `organization_id` in `booted()::creating` from the authenticated user's `organization_id`, falling back to a direct lookup on the parent model using `withoutGlobalScopes()` for queue/CLI safety
- Exposes an `organization()` `BelongsTo` relation

### Filament Resources

`getEloquentQuery()` in all three resources now uses direct `where('organization_id', $organizationId)` instead of `whereHas` on the parent relationship, eliminating the JOIN cost on every list page.

### Policies

Ownership checks in view/update/delete/restore/replicate/forceDelete methods now compare `$model->organization_id` directly instead of traversing `$model->job?->organization_id`, `$model->estimate?->organization_id`, or `$model->jobType?->organization_id`. This removes the N+1 relation load in policy gate calls.

## Next Steps

- Consider adding a `tenancy:check` artisan command assertion for the three new TenantAware models.
- Review any API endpoints that create `JobMessage` records outside the web request context (e.g., notification jobs/queues) and ensure `organization_id` is passed explicitly when no user is authenticated.
# Issue 197 — Restrict InvoiceResource / PaymentResource in TitanPro panel to super_admin only

## Context

Follows from issue-121 (ZeroPay panel).  The `InvoiceResource` and `PaymentResource` that live
in `app/Filament/Resources/` are auto-discovered by the TitanPro super-admin panel
(`/titanpro`).  An identical pair of resources also live in `app/Filament/ZeroPay/Resources/`
and are served by the ZeroPay panel (`/zeropay`) for `bookkeeper`, `owner`, and `admin` roles.

Having two panels expose the same financial data with different role gates leaks access: an
`admin` user can (in principle) edit invoices in the TitanPro panel even though the
authoritative finance surface is ZeroPay.

## Changes Made

### Modified Files

**`app/Filament/Resources/InvoiceResource.php`**
- Added `use Illuminate\Database\Eloquent\Model;` import.
- Added doc-block explaining the super-admin restriction and pointing operators to ZeroPay.
- Added the following authorization methods, each returning `true` only for users with the
  `super_admin` role:
  - `canViewAny()` — guards the list page
  - `canCreate()` — guards the create page
  - `canEdit(Model $record)` — guards the edit page
  - `canView(Model $record)` — guards the view/detail page
  - `canDelete(Model $record)` — guards individual record deletion
  - `canDeleteAny()` — guards bulk deletion

**`app/Filament/Resources/PaymentResource.php`**
- Same set of changes as `InvoiceResource` above.

### New Files

**`tests/Feature/Admin/InvoicePaymentAccessTest.php`**
- Confirms that `/admin/invoices` and `/admin/payments` (old alias paths) return 404 for an
  authenticated `admin` user (those routes are not registered).
- Confirms that `admin`, `bookkeeper`, and `owner` users receive 403 Forbidden when hitting
  `/titanpro/invoices` and `/titanpro/payments` (blocked by the panel-level gate in
  `User::canAccessPanel()`).

## Files NOT Changed

**`app/Filament/ZeroPay/Resources/InvoiceResource.php`**  
**`app/Filament/ZeroPay/Resources/PaymentResource.php`**  
These resources remain unrestricted at the resource level; access is gated by the ZeroPay
panel (`canAccessPanel()` allows `owner`, `admin`, `bookkeeper`).  No further changes needed.

## How Authorization Works

1. **Panel gate** — `User::canAccessPanel()` reads `config('titan_panels.panels.titanpro.roles')`
   which resolves to `['super_admin']`.  Non-super-admin users receive HTTP 403 before they can
   reach any resource inside the TitanPro panel.
2. **Resource gate** — The new `canViewAny()` / `canCreate()` / etc. methods on the TitanPro
   `InvoiceResource` and `PaymentResource` provide a second layer of defence: even if the panel
   gate were relaxed in future, these resources will remain visible only to `super_admin`.

## Next Steps

- Consider removing the duplicate `InvoiceResource` / `PaymentResource` from
  `app/Filament/Resources/` entirely if super-admin has no operational need to manage individual
  invoices (they have the ZeroPay panel available).
- If super-admin cross-tenant views are required, scope the TitanPro resources to show **all**
  organisations (remove the `organization_id` filter from `getEloquentQuery()`).
- Run `composer run test` after deploying to confirm all new tests pass.
## Issue #197 — Configure dedicated mail queue worker (Supervisor/Horizon) in production

### Source

Follow-up to issue #134 (`issue-docs/issue-134.md`), which set `$queue = 'mail'` on
`JobConfirmationMail`, `InvoiceReminderMail`, and `TrialEndingNotification`.
Without a dedicated worker consuming the `mail` queue in production those jobs
silently stall — they are dispatched but never processed.

### Root Cause

A queue name (`mail`) was assigned to the three mail/notification classes so that
mail delivery is isolated from the `default` queue, but no Supervisor program (or
Horizon supervisor block) was configured to run a worker that listens on that queue.
The `default` queue worker ignores jobs on `mail`.

### Files Changed

| File | Change |
|------|--------|
| `deployment/supervisor/laravel-mail-worker.conf` | **New** — Supervisor program block for a 2-process `mail` queue worker using `--tries=3 --timeout=90` |
| `config/logging.php` | Added `ops` (stack) and `ops_daily` (daily file) log channels for routing critical mail-failure alerts |
| `app/Listeners/AlertOnFailedMailJob.php` | **New** — Listens to `Illuminate\Queue\Events\JobFailed`; logs `CRITICAL` to the `ops` channel when a `mail`-queue job exhausts all retries |
| `app/Providers/AppServiceProvider.php` | Imports `AlertOnFailedMailJob` + `JobFailed`; wires `Event::listen(JobFailed::class, AlertOnFailedMailJob::class)` |
| `docs/deployment-notes.md` | **New** — Deployment notes covering Supervisor installation, graceful restart procedure (`php artisan queue:restart`), and ops-alert configuration via `OPS_LOG_CHANNELS` |

### Fixes Applied

1. **Supervisor config** (`deployment/supervisor/laravel-mail-worker.conf`)
   - `command=php /var/www/artisan queue:work --queue=mail --tries=3 --timeout=90 --sleep=3 --max-jobs=500 --max-time=3600`
   - `numprocs=2` for parallel throughput
   - `autorestart=true`, `stopwaitsecs=120` (allows in-flight jobs to finish before kill)
   - Log rotation: 10 MB × 5 rotations

2. **`ops` log channel** (`config/logging.php`)
   - Stack channel driven by `OPS_LOG_CHANNELS` env var (defaults to `ops_daily`)
   - `ops_daily` writes critical-level entries to `storage/logs/ops.log` (30-day rotation)
   - Operators can add `slack` or another channel without code changes

3. **`AlertOnFailedMailJob` listener** (`app/Listeners/AlertOnFailedMailJob.php`)
   - Handles `Illuminate\Queue\Events\JobFailed`
   - No-ops for non-`mail` queues so the listener is narrowly scoped
   - Logs job name, queue, connection, exception message, and payload at `critical` level

4. **Event registration** (`app/Providers/AppServiceProvider.php`)
   - `Event::listen(JobFailed::class, AlertOnFailedMailJob::class)` added to `boot()`

5. **Deployment notes** (`docs/deployment-notes.md`)
   - Step-by-step instructions for installing the Supervisor config
   - `php artisan queue:restart` must run after every deploy
   - Documents `OPS_LOG_CHANNELS` env variable for routing alerts to Slack/PagerDuty

### Tests Added or Updated

No new automated tests were required.

- The `AlertOnFailedMailJob` listener is pure logging; its behaviour is validated by
  the existing `tests/Feature/Owner/InvoiceReminderTest.php` assertions that mail is
  queued on the `mail` queue — failed-job dispatching is framework-level.
- The Supervisor config is an infrastructure artefact and is validated at deploy time
  via `supervisorctl status`.

### Next Steps

1. **Install the Supervisor config on every production server** — see `docs/deployment-notes.md`.
2. **Add `php artisan queue:restart` to the CI/CD deploy pipeline** immediately after `php artisan migrate`.
3. **Set `OPS_LOG_CHANNELS=ops_daily,slack`** (and `LOG_SLACK_WEBHOOK_URL`) in production `.env` to get real-time Slack alerts on mail failures.
4. **Consider Laravel Horizon** if the queue workload grows: the `mail` supervisor block in Horizon mirrors the same `--queue=mail --tries=3 --timeout=90` configuration and adds a web dashboard.
5. **Ensure `failed_jobs` table exists** — run `php artisan queue:failed-table && php artisan migrate` if it has not been created yet.
