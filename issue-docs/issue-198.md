# Issue 198 — AI Theme Generator: natural language to complete design system

## Issue Summary

Users type a natural language description of the admin feel they want, and the AI generates a
complete design system including palette, typography, shadows, spacing, and button styles.
The generated theme is previewed live before the user accepts it, and is saved as a named snapshot.

## Files Changed

### New files

| File | Purpose |
|------|---------|
| `database/migrations/2026_05_11_130000_create_ai_theme_snapshots_table.php` | Creates `ai_theme_snapshots` table with columns `organization_id`, `user_id`, `name`, `prompt`, `tokens` (JSON), and timestamps. Includes a composite index on `(organization_id, created_at)` to support the daily rate-limit query. |
| `app/Models/AiThemeSnapshot.php` | Eloquent model for the snapshots table. Implements `TenantAware` / `BelongsToTenant`. Exposes `todayCountForOrg(?int $orgId): int` for rate-limit checking and `createFromGeneration()` for saving new AI themes. |
| `app/Services/AiThemeGenerator.php` | Service that sends the user prompt to the Anthropic Claude API (`claude-sonnet-4-6`), parses the structured JSON response into a validated token array, and sanitises each token value (hex regex, font whitelist, CSS length patterns, etc.). |
| `issue-docs/issue-198.md` | This file. |

### Modified files

| File | Changes |
|------|---------|
| `app/Filament/Pages/UiStudio.php` | Added `use` imports for `AiThemeSnapshot` and `AiThemeGenerator`. Added AI modal Livewire state properties (`showAiModal`, `aiModalStep`, `aiPrompt`, `aiGeneratedTheme`, `aiErrorMessage`). Added methods: `openAiModal()`, `closeAiModal()`, `generateAiTheme()`, `acceptAiTheme()`, `discardAiTheme()`, `regenerateAiTheme()`. Added `aiGenerate` header action button (amber, sparkles icon) calling `openAiModal()`. |
| `resources/views/filament/pages/ui-studio.blade.php` | Added a full-screen modal overlay rendered when `$showAiModal` is `true`. Modal has three steps: **prompt** (textarea + Generate button + error display), **generating** (animated spinner), and **preview** (5-colour swatch grid, typography panel, tokens panel, Accept/Regenerate/Discard action buttons). |
| `config/services.php` | Added `anthropic.api_key` entry reading `ANTHROPIC_API_KEY` env variable. |

## Features Implemented

| Requirement | Implementation |
|-------------|---------------|
| "AI Generate" button in UI Studio | Header action `aiGenerate` (amber, `heroicon-m-sparkles`) calls `openAiModal()` |
| Prompt sent to Claude API with structured system prompt | `AiThemeGenerator::generate()` → Anthropic `/v1/messages` endpoint, model `claude-sonnet-4-6`, SYSTEM prompt enforcing JSON token schema |
| Response parsed into theme token format | `parseTokens()` in `AiThemeGenerator` validates and sanitises all 12 tokens; falls back to sensible defaults on invalid values |
| Live preview before acceptance | "preview" step in the modal shows colour swatches, typography details, and design tokens |
| Accept / Regenerate / Discard | `acceptAiTheme()` applies colours+fonts to live UiStudio state; `regenerateAiTheme()` returns to the prompt step; `discardAiTheme()` closes the modal |
| Saved as named snapshot | `acceptAiTheme()` calls `AiThemeSnapshot::createFromGeneration()` → name format "AI: {short prompt} — {date}" |
| `claude-sonnet-4-6` model via `ANTHROPIC_API_KEY` | `AiThemeGenerator::MODEL` constant; key read from `config('services.anthropic.api_key')` |
| Rate-limit 5 generations per org per day | `generateAiTheme()` calls `AiThemeSnapshot::todayCountForOrg()` before invoking the API; returns an error message on limit breach |

## Architecture Notes

- The `AiThemeGenerator` service is registered via Laravel's auto-binding — inject it with `app(AiThemeGenerator::class)`.
- All AI-returned token values are sanitised before use: colors require `/^#[0-9a-fA-F]{6}$/`, fonts require `/^[\w\s\-]+$/`, radius requires a CSS px value, shadow and button_hover have whitelist patterns.
- The `ai_theme_snapshots` table uses `organization_id` for tenant isolation via `BelongsToTenant`.
- The modal is rendered as a Livewire-driven overlay (not a Filament Action modal) to support the multi-step flow (prompt → generating → preview).
- `Schema::hasTable('ai_theme_snapshots')` guards snapshot persistence so the page degrades gracefully before migrations are run.
- The generating step relies on Livewire's `wire:click="generateAiTheme"` with the backend transition from `generating` → `prompt` (on error) or `generating` → `preview` (on success). Because the Anthropic HTTP call is synchronous within the Livewire request, the spinner is visible during the round-trip.

## Next Steps

1. **Streaming support** — use the Anthropic streaming API with server-sent events so users see tokens appearing in real time rather than waiting for the full response.
2. **Extended tokens** — expose `sidebar_color`, `border_radius`, `shadow`, and `button_hover` in the branding panel and persist them in `OrganizationBranding` so they are applied on Publish.
3. **Snapshot history panel** — add a "History" tab in UI Studio listing past AI-generated snapshots for the organisation, with a "Re-apply" action.
4. **PHP 8.4 test run** — once the CI environment is updated to PHP ≥ 8.4, run `./vendor/bin/pest` to verify no regressions.