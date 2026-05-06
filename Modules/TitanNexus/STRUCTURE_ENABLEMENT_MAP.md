# TitanNexus Complete Module Structure Enablement Map

## Package identity
- `module.json` enables module discovery, dependency declarations, service provider registration, and install metadata.
- `module.lock.json` enables reproducible installs, source checksum tracking, and safe upgrades.
- `version.json` enables compatibility gates, migrations, rollbacks, and release checks.
- `README.md` enables onboarding.
- `CHANGELOG.md` enables release audit history.
- `LICENSE` enables distribution boundaries.

## Manifests
- `manifests/module.manifest.json` enables module registry/boot priority.
- `navigation.manifest.json` enables sidebar/menu injection.
- `permissions.manifest.json` enables role/permission loading.
- `routes.manifest.json` enables route discovery.
- `events.manifest.json` enables event publishing.
- `listeners.manifest.json` enables decoupled event reactions.
- `policies.manifest.json` enables authorization mapping.
- `migrations.manifest.json` enables ordered schema upgrades.
- `database.manifest.json` enables table/model ownership.
- `ui.manifest.json` enables pages, widgets, cards, forms, tables.
- `api.manifest.json` enables external/internal API contracts.
- `automation.manifest.json` enables triggers, handlers, pipelines, schedules.
- `workflows.manifest.json` enables visual campaign flows.
- `tenancy.manifest.json` enables company isolation rules.
- `billing.manifest.json` enables plans, meters, limits, usage.
- `search.manifest.json` enables lead/campaign indexing.
- `ai.manifest.json` enables AI agents, tools, prompts, pipelines, memory.
- `upgrade.manifest.json` enables MarketingBot import and upgrade flow.

## Config and providers
- `Config/` enables runtime toggles for features, permissions, navigation, routes, UI, tenancy, billing, search, and AI.
- `Providers/` enables the host app to boot TitanNexus cleanly: routes, events, policies, repositories, automation, workflows, tenancy, billing, search, Filament, and final boot hooks.

## Routes
- `Routes/web.php` enables browser/SPA entry points.
- `Routes/api.php` enables public/API client access.
- `Routes/internal.php` enables service-to-service calls.
- `Routes/tenant.php` enables tenant-scoped routes.

## Database and domain core
- `Database/migrations` enables tables for leads, campaigns, messages, metrics, bookings.
- `seeders`, `factories`, `states` enable defaults, demo data, tests, and lifecycle fixtures.
- `Models/` enables persistent objects like Lead, Campaign, OutreachMessage.
- `Policies/` enables access control.
- `Repositories/` enables replaceable persistence.
- `DTOs/` enables safe data transfer between UI/API/actions/jobs.
- `ValueObjects/` enables validated concepts like LeadScore and Vertical.
- `Enums/` enables stable statuses/channels/stages.
- `Casts/` enables custom serialization.
- `Actions/` enables single-purpose business commands.
- `Services/` enables reusable engines: sequencing, scoring, discovery, booking handoff.
- `Queries/` enables dashboard/report queries.
- `Scopes/` enables reusable model filters.
- `Observers/` enables lifecycle side effects.

## Events, listeners, jobs, notifications, mail
- `Events/` enables signals such as CampaignLaunched, LeadQualified, FollowupDue.
- `Listeners/` enables decoupled reactions: queue follow-ups, update metrics, push to Ground Zero.
- `Jobs/` enables queued/background work: scraping, outreach sending, AI scoring.
- `Notifications/` enables internal alerts and cross-channel notices.
- `Mail/` enables email classes/templates for outreach and follow-up.
- `Imports/` enables CSV/imported lead ingestion.
- `Exports/` enables campaign, lead, and metrics exports.

## Workflows and automation
- `Workflows/Definitions` enables drag-drop campaign schemas.
- `Steps` enables reusable flow nodes.
- `Conditions` enables branches such as replied/not replied.
- `Transitions` enables state movement like qualified to booked.
- `Guards` enables safety checks such as campaign budget/plan limits.
- `Automation/Triggers` enables time/event/data activation.
- `Handlers` enables execution units.
- `Pipelines` enables multi-step automations.
- `Schedulers` enables recurring follow-up scans and campaign maintenance.

## HTTP, console, contracts, support
- `Http/Controllers` enables request handlers.
- `Middleware` enables tenant, permission, and rate checks.
- `Requests` enables validation.
- `Resources` enables API response shapes.
- `Console/Commands` enables CLI imports/maintenance.
- `Console/Schedules` enables scheduler registration.
- `Contracts/` and `Interfaces/` enable swappable engines.
- `Traits/` enables reusable model/service behavior.
- `Support/` preserves source archives and internal utilities.
- `Helpers/` enables shared helper functions.

## UI and Filament
- `Resources/views` enables Blade fallbacks.
- `Resources/lang` enables localization.
- `Resources/assets` enables module CSS/JS.
- `Filament/Plugin` enables plugin registration.
- `Resources` enables CRUD panels.
- `Pages` enables custom consoles.
- `Widgets` enables AI cards and KPIs.
- `RelationManagers` enables nested relations.
- `Tables` enables data grids.
- `Forms` enables campaign/lead forms.
- `Actions` enables one-click tasks.
- `Filters` enables list filtering.
- `Infolists` enables detail panels.
- `Clusters` enables grouped navigation.
- `Support` enables UI helper classes.

## AI, tenancy, billing, search, API
- `AI/Agents` enables specialized AI workers.
- `Tools` enables callable functions like target scraping.
- `Prompts` enables versioned prompt templates.
- `Pipelines` enables multi-step AI flows.
- `Memory` enables campaign/lead context retention.
- `Tenancy/Resolvers` enables tenant detection.
- `Tenancy/Scopes` enables data isolation.
- `Tenancy/Policies` enables tenant-specific authorization.
- `Billing/Plans` enables packaged subscriptions.
- `Meters` enables usage measurement.
- `Usage` enables recording billable actions.
- `Limits` enables plan enforcement.
- `Search/Indexes` enables searchable entities.
- `Mappers` enables index documents.
- `Transformers` enables search result output.
- `API/Transformers` enables response transformation.
- `Serializers` enables JSON/API formatting.
- `Contracts` enables stable integration promises.

## Tests and upgrade
- `Tests/Unit` enables isolated domain tests.
- `Tests/Feature` enables endpoint/workflow tests.
- `Tests/Integration` enables cross-module tests, especially Ground Zero handoff.
- `Upgrade/Migrations` enables schema conversion.
- `Upgrade/Scripts` enables MarketingBot import.
- `Upgrade/Hooks` enables preflight/postflight checks.
