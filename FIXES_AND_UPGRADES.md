# TitanPro — System Fixes & Upgrades Reference

> Generated: 2026-05-17  
> Source: GitHub Issues audit (#140–#612) + deep repo scan  
> Status: Work in progress — check off items as completed on server/in repo

---

## HOW TO USE THIS FILE

Each item has a checkbox. Tick it when the fix is live on the server or merged into the repo.  
Items are grouped by domain. Work top-to-bottom within each group — later items often depend on earlier ones.

---

## 1. CRITICAL SECURITY FIXES

- [ ] **#570** `TitanZero Gateway` accepts full data envelopes — replace with signal-only ingestion (no arbitrary payload passthrough)
- [ ] **#572** `TitanTalk DialogGrid` controller mass-assigns from `$request->all()` — add `FormRequest` whitelists for every action
- [ ] **#571** `ZeroPay` agent chat/command endpoints pass `$request->all()` without schema — enforce strict signal envelope
- [ ] **#573** `BudgetActual` and `Asset` controllers use `$request->all()` instead of `$request->validated()`
- [ ] **#524** Missing `Authorization Policies` for `User`, `Organization`, `Subscription`, `WorkflowInstance` models
- [ ] **#523** `ZeroPayWebhookController` missing webhook signature verification + auth missing on theme routes
- [ ] **#522** `withoutGlobalScopes()` used without admin context — potential cross-tenant data bypass

---

## 2. MULTI-TENANT / CREDENTIAL MANAGEMENT

- [ ] **#583** UMBRELLA: Every external service must support user-supplied (per-tenant) API keys
- [ ] **#582** Build Filament "Integrations" settings hub — unified UI for all per-tenant API key management
- [ ] **#581** AWS S3 storage — add per-tenant bucket/credential override to `organisation_settings`
- [ ] **#580** Postmark, Resend, Slack — add per-tenant key override to `organisation_settings`
- [ ] **#579** PayPal and Cryptomus gateway adapters are stubs — implement real credential storage + per-tenant key mgmt
- [ ] **#578** TitanAI custom provider endpoint/key — wire per-tenant override into `company_ai_keys`
- [ ] **#577** Voice AI providers (Bland AI, VAPI AI) — add per-tenant key override + tenant-scoped credential storage
- [ ] **#576** Vector store providers (Qdrant, Pinecone, Meilisearch) — add per-tenant config override
- [ ] **#387** Normalise module tenancy keys (`company_id` / `tenant_id` / `organization_id`) to single boundary column

---

## 3. DATA ARCHITECTURE & PRIVACY

- [ ] **#575** Enforce local-first data architecture: all user data in IndexedDB; server receives signals only
- [ ] **#574** Add outbound payload logging middleware for AI and gateway routes to detect data leakage
- [ ] **#389** Audit Filament relation managers and custom list pages for `getEloquentQuery()` bypass
- [ ] **#386** Cross-tenant policy tests for `CRMCore` `LeadPolicy` and module activity-log policy paths

---

## 4. BACKEND / PLATFORM INFRASTRUCTURE

- [ ] **#529** `Media::team()` references non-existent class + automation migrations use wrong tenant column name
- [ ] **#525** `DashboardController` returns hardcoded stub stats — replace with real DB queries
- [ ] **#384** Add PHP 8.2 / 8.3 / 8.4 matrix to production-check CI workflow

---

## 5. MODULE UPGRADES — STRUCTURAL FIXES (all modules)

These apply across the entire module fleet:

- [ ] **#548** `Tests/` canonical structure incomplete across all modules — add Integration, API, Security, AI test dirs
- [ ] **#547** `Contracts/` and `Support/` directories missing from most modules — add per blueprint spec
- [ ] **#546** Canonical `Config/` files missing across multiple modules — billing, notifications, integrations, workflows
- [ ] **#545** Canonical manifests missing across 20+ modules — navigation, widgets, automation, health manifests
- [ ] **#544** `AIServiceProvider` and `WorkflowServiceProvider` missing from most modules
- [ ] **#543** `Bootstrap/` directory pattern missing from all modules — implement canonical module bootstrap
- [ ] **#541** `GroundZeroOps` and `InstantAds` missing `composer.json` — Composer autoloading broken
- [ ] **#533** Broken `module.json` dependencies — `ServiceManagement` and `Core` modules listed as missing
- [ ] **#534** Stub service providers across `NexusGrowth`, `TitanProAdmin`, `CleaningJobs`, `TitanRewind`, `TitanCore`

---

## 6. MODULE UPGRADES — PER-MODULE

### HRCore
- [ ] **#439** Fix non-standard structure to canonical Blueprint 05; add Filament, signals, full tests
- [ ] **#539** Migrate `/app` directory structure to canonical NWIDART module pattern

### TitanTalk
- [ ] **#440** Add Filament, `filament_panel`, AI tools, Reverb real-time, manifests, and tests
- [ ] **#542** TitanTalk has no Filament directory — cannot register with any panel

### TitanNexus
- [ ] **#531** 23+ empty stub classes — implement core business logic
- [ ] **#452** Complete v16–19 upgrade pass, audit 10 AI tools, harden to v1.0 release

### Asset Management
- [ ] **#441** Add Filament, `filament_panel`, AI tools, signal wiring, tenant scoping, and full tests

### SupplyChain
- [ ] **#443** Add AI demand forecasting, complete Filament, wire signals, full test coverage

### CleanQuality
- [ ] **#444** Add AI inspection scoring, complete Filament, wire signals, full test coverage

### TitanDocs
- [ ] **#445** Add AI SWMS generation, complete Filament resources, wire signals, and tests

### TitanHello
- [ ] **#446** Audit legacy bridge, complete AI voice integration, Filament inbox, manifests, and tests

### ProShots
- [ ] **#435** Add Filament, AI manifest, error handling, tenant scoping, and tests

### ZeroPayHub
- [ ] **#433** Implement full module from shell — payment operations hub (new implementation)

### TitanStudioHub
- [ ] **#431** Implement full module from shell — creative ops hub (new implementation)

### TitanSoloDash
- [ ] **#430** Implement full module from shell — solo operator command centre (new implementation)

### CRMCore
- [ ] **#536** `ConversationExport` empty stub + commented-out AI tools in `config/titan-ai.php`
- [ ] **#532** Manifest drift — 4 AI tools declared but implementation classes missing

---

## 7. FRONTEND / TYPESCRIPT

- [ ] **#535** Wayfinder `route()` helper not typed globally — TypeScript errors in Owner pages and auth pages

---

## 8. CHATBOT-OS — AI CHAT SYSTEM

### Infrastructure / Core
- [ ] **#584** Add SSE streaming responses to `BusinessOsChatPanel` — replace blocking JSON fetch
- [ ] **#591** Generation stage status indicator — animated states for waiting, streaming, tool calls
- [ ] **#596** Auto-scroll with smart lock — `ScrollableMessageContainer` for the thread
- [ ] **#597** Auto-generated thread naming using LLM summarisation
- [ ] **#598** Persistent cross-session AI memory extraction and injection
- [ ] **#608** Tool call limit guardrails — max tool calls per run with UI indicator

### Chat Panel UI
- [ ] **#586** Thread history sidebar — collapsible list with search and new-thread button
- [ ] **#587** File and image attachment support in chat composer
- [ ] **#588** Resizable chat panel with drag handle and Cmd+K toggle
- [ ] **#589** Tool call visualization — expandable UI for in-progress and completed tool calls
- [ ] **#590** AI reasoning / thinking steps — collapsible `ReasoningInfo` component
- [ ] **#592** `@mention` context attachments in composer — resource picker with autocomplete
- [ ] **#603** Floating control bar (ControlBar) — Cmd+K modal chat launcher
- [ ] **#604** Full-page thread layout — dedicated `/chat` route with `MessageThreadFull`
- [ ] **#606** Thread dropdown — quick-switch between threads from the panel header
- [ ] **#609** Collapsible embedded chat — `MessageThreadCollapsible` widget for module pages

### Message Rendering
- [ ] **#585** Markdown rendering with syntax highlighting for assistant messages
- [ ] **#594** Generative UI — AI-rendered components in the message thread
- [ ] **#605** Image display in AI responses — `MessageImages` component

### AI Features
- [ ] **#593** MCP (Model Context Protocol) integration — prompt templates and resource browser in chat
- [ ] **#595** Elicitation UI — structured AI clarification dialogs within the chat thread
- [ ] **#607** Per-project custom LLM parameters — temperature, max tokens, top-p editor

### Widgets
- [ ] **#600** Generative chart widget — AI-rendered graphs and data visualisations inline
- [ ] **#601** Generative form widget — AI-rendered input forms for data collection
- [ ] **#602** Generative map widget — AI-rendered geographic visualisations

### Actions & Feedback
- [ ] **#610** Edit-with-AI button — inline AI refinement for generated content blocks
- [ ] **#611** Message-level feedback and copy actions — thumbs up/down + copy button on messages
- [ ] **#612** Audio transcription endpoint — server-side Whisper for voice-to-text (`POST /api/titan/audio/transcribe`)

### Observability & Analytics
- [ ] **#599** Thread observability dashboard — message viewer with tool calls, tokens, and search
- [ ] **#504** Implement conversation export + DB-backed analytics dashboard

### Integrations
- [ ] **#507** Integrate Tambo SDK, wire `useTamboVoice` + `useMessageImages` into OS shell and portal widget
- [ ] **#508** Implement `ChatbotChannelWebhook` model + verify WhatsApp/Telegram/Messenger/Instagram webhook handlers
- [ ] **#499** Implement canned responses system — model, API, Filament CRUD, widget integration

---

## 9. UI STUDIO / THEMING

- [ ] **#358** Deploy BOS dashboard shell — 8-hub master dashboard with manifests and KPI widgets
- [ ] **#375** Add Filament `RoleUIProfile` resource (full CRUD) alongside UI Studio tab
- [ ] **#376** Add `titan:role-ui:reset` artisan command to clear role UI profiles
- [ ] **#377** Add per-role `font_heading` / `font_body` overrides to `RoleUIProfile`
- [ ] **#373** Frontend: consume `role_ui.hidden_nav_items` in Vue sidebar / navigation
- [ ] **#378** Add `WidgetPropertyRegistry::extend()` hook for module-registered widget schemas
- [ ] **#379** Add `refresh_interval` property to UiStudio canvas widgets
- [ ] **#380** Server-side validation for `WidgetPropertyRegistry` values on publish
- [ ] **#381** AI theme generator: stream Anthropic response for live token-by-token preview
- [ ] **#382** Persist extended brand tokens (sidebar_color, border_radius, shadow, button_hover) in `OrganizationBranding`
- [ ] **#383** Add AI theme snapshot History tab to UI Studio with re-apply action
- [ ] **#332** Build `ComponentOverrideCssService` to inject CSS from `titan_ui_component_overrides`
- [ ] **#333** Add tenant selector to UI Studio Components tab for cross-tenant design management
- [ ] **#334** Import/export Component Design System presets as JSON
- [ ] **#338** Add `theme_packs` table and CRUD for user-uploaded custom themes
- [ ] **#339** Rate-limit and expire `shared_themes` share tokens
- [ ] **#147** Theme versioning and history — rollback points, change history, diff view (`titan_theme_versions` table)
- [ ] **#140** Responsive UI Designer — preview and tune layout for desktop/tablet/mobile/collapsed sidebar
- [ ] **#153** Verify: Universal Palette System — semantic presets, coordinated tokens, hover/focus variants
- [ ] **#154** Verify: Theme Customizer — live palette editing, snapshots, preset switching, save/restore
- [ ] **#218** Apply persisted layout tokens beyond `PlatformLayout` to remaining runtime shells
- [ ] **#279** UI Studio: replace placeholder thumbnails with sandboxed iframe live preview
- [ ] **#280** UI Studio: persist menu items via `FilamentMenuBuilder` or dedicated table

---

## 10. REPO SCAN FINDINGS (additional items not in GitHub issues)

> Added from automated deep repo scan — prioritised by severity

### CRITICAL — Fix immediately

- [ ] **`app/Models/User.php` lines 54-59 and 76-81** — `hasConfirmedTwoFactor()` method is defined **twice** (identical implementations). Remove the duplicate.
- [ ] **`company_id` vs `organization_id` mismatch** — TitanNexus and Workflow models use `company_id` but the rest of the platform uses `organization_id`. Migration `2026_05_17_160900_add_company_scope_to_titannexus_tables.php` added `company_id` to 13 tables. Standardise on `organization_id` across: `WorkflowAuditLog`, `AutomationRun`, `WorkflowInstance`, `NexusContact`, `TitanNexusCampaign`, `NexusConversation`, `NexusCallEvent`, `TitanNexusLead`, `NexusOutreachRun`, `NexusCallRecording`, `NexusVoiceCallLog`, `NexusCallSession`, `NexusBookingHandoff`, `NexusCallbackRequest`, `NexusContractDocument`.

### HIGH — Authorization & Tenant Isolation

- [ ] **Missing authorization policies for 40+ models** including: `EstimateLineItem`, `InvoiceLineItem`, `JobLineItem`, all `NexusContact`/`NexusConversation`/`NexusCallEvent`/`NexusOutreachRun`/`NexusCallRecording`/`NexusCallSession`/`NexusBookingHandoff`/`NexusCallbackRequest`/`NexusContractDocument`/`NexusTrainingContent`, `AiThemeSnapshot`, `AutomationRun`, `FoundingMemberCoupon`, `LeadPipelineEntry`, `MarketingCampaign`.
- [ ] **Line item models (`EstimateLineItem`, `InvoiceLineItem`, `JobLineItem`)** have no direct `organization_id` column — they can only be scoped by traversing the parent chain, creating authorization blind spots. Add direct `organization_id` FK for policy enforcement.
- [ ] **`withoutGlobalScopes()` audit** — confirm all usages are admin-only contexts; a non-admin path through this bypasses tenant isolation.

### HIGH — Missing Business Logic

- [ ] **`app/Http/Controllers/Owner/DashboardController.php` line 21** — Stats are hardcoded (`open_jobs: 12`, `today_visits: 7`, etc.). Replace with real `Job` / `Visit` model queries scoped by `organization_id`.
- [ ] **`app/Http/Controllers/Owner/InvoiceController.php` line 120** — `TODO: dispatch InvoiceSent notification` — the notification class needs to be created and dispatched here.
- [ ] **Missing event listeners** — 7 domain events exist (`JobCreated`, `JobStatusChanged`, `EstimateSent`, `DriverLocationUpdated`, `AutomationFailed`, `UsageLimitExceeded`, `UsageLimitApproaching`) but fewer than 4 listeners are registered. Wire all events to listeners.
- [ ] **Only 1 notification class exists** (`TrialEndingNotification`) — add: `InvoiceSentNotification`, `EstimateSentNotification`, `JobStatusChangedNotification` at minimum.
- [ ] **Only 3 queue Job classes** (`ExecuteAutomationJob`, `WorkflowStepJob`, `WorkflowStepDeadLetterJob`) — async tasks like email dispatch, AI calls, report generation likely need dedicated jobs.

### MEDIUM — Configuration

- [ ] **Duplicate `OPENAI_API_KEY` in `.env.example`** — defined twice (line ~100 as "Titan Model Runtime" and line ~157 as "AI Providers — Primary provider"). Deduplicate.
- [ ] **Wildcard package versions in `composer.json`** — `bezhansalleh/filament-panel-switch: "*"` and `novadaemon/filament-combobox: "*"` should be pinned to specific versions.
- [ ] **Dual Vite config files** — both `vite.config.js` and `vite.config.ts` exist at root. Consolidate to `vite.config.ts` only.
- [ ] **`config/titango-pwa.php`** is 64 bytes (nearly empty) — complete PWA configuration.
- [ ] **AI config key naming inconsistency** — `OPENAI_MODEL`, `OPENAI_CHAT_MODEL`, `OPENAI_EMBED_MODEL` are separate env vars; document which is canonical for each use case.

### MEDIUM — TypeScript / Frontend

- [ ] **340+ instances of `any` / `@ts-ignore` / `unknown`** across `resources/js/` — systematically improve type coverage to catch runtime errors early.
- [ ] **`modules_statuses.json`** and **`migration_status.txt`** at root are runtime/build artefacts — add to `.gitignore`.
- [ ] **`DELTA_MANIFEST.json`** at root — move to `docs/` or add to `.gitignore` if auto-generated.

### LOW — Code Quality

- [ ] Pin wildcard versions in `composer.json` (see Medium above).
- [ ] Add `RefreshDatabase` + `organization_id` fixture helpers to `TestCase` base class for consistent multi-tenant test setup.
- [ ] Run `./vendor/bin/pest --coverage` to establish a coverage baseline — invoice/estimate/job-dispatch flows likely missing test coverage.

---

## COMPLETION SUMMARY

| Category | Total Items | Done |
|---|---|---|
| Critical Security | 7 | 0 |
| Multi-Tenant Credentials | 9 | 0 |
| Data Architecture | 4 | 0 |
| Backend Infrastructure | 3 | 0 |
| Module Structural Fixes | 9 | 0 |
| Per-Module Upgrades | 21 | 0 |
| Frontend/TypeScript | 1 | 0 |
| Chatbot-OS | 32 | 0 |
| UI Studio/Theming | 22 | 0 |
| Repo Scan Additional | 21 | 0 |
| **TOTAL** | **129** | **0** |
